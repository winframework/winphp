<?php

namespace Win\Repositories\Database;

use Win\Common\Pagination;
use Win\Models\Model;
use Win\Request\HttpException;

/**
 * Object Relational Mapping
 */
abstract class Orm
{
	/** @var string */
	const TABLE = '';

	/** @var string */
	const PK = 'id';

	/** @var Connection */
	public $conn;

	/** @var Pagination */
	public $pagination;

	/** @var Sql */
	protected $sql;

	/**
	 * @param Model $model
	 * @return mixed[]
	 */
	abstract public static function mapRow($model);

	abstract public static function mapModel($row);

	/**
	 * @param Connection $connection
	 */
	public function __construct($connection = null)
	{
		$this->conn = $connection ?: MysqlConnection::instance();
		$this->sql = new Sql(static::TABLE);
		// $this->pagination = new Pagination();
	}

	/**
	 * Prepara raw Sql
	 * @param string $query
	 * @param mixed[]
	 */
	public function raw($query, ...$values)
	{
		$this->sql = new Sql(static::TABLE, $values, $query);

		return $this;
	}

	/**
	 * Executa SQL
	 * @param string $query
	 * @param mixed[]
	 */
	public function execute($query, ...$values)
	{
		return $this->conn->execute($query, $values);
	}

	/**
	 * Retorna o primeiro resultado da busca
	 * @return Model
	 */
	public function one()
	{
		$this->sql->setLimit(0, 1);

		$query = $this->sql->select();
		$values = $this->sql->getValues();
		$row = $this->conn->fetch($query, $values);
		$this->flush();

		return $this->mapModel($row);
	}

	/**
	 * Retorna o primeiro resultado da busca ou redireciona para Página 404
	 */
	public function oneOr404()
	{
		$model = $this->one();
		if ($model->id === 0) {
			throw new HttpException('Model not found', 404);
		}

		return $model;
	}

	/**
	 * Retorna o registro pela PK
	 * @param int $id
	 */
	public function find($id)
	{
		return $this->filterBy(static::PK, $id)->one();
	}

	/**
	 * Retorna o registro pela PK ou redireciona para Página 404
	 * @param int $id
	 */
	public function findOr404($id)
	{
		return $this->filterBy(static::PK, $id)->oneOr404();
	}

	/**
	 * Retorna o todos os resultados da busca
	 */
	public function list()
	{
		if ($this->pagination) {
			$this->setLimit();
		}

		$query = $this->sql->select();
		$values = $this->sql->getValues();
		$rows = $this->conn->fetchAll($query, $values);
		$this->flush();

		return array_map([$this, 'mapModel'], $rows);
	}

	/**
	 * Retorna o total de resultados da busca
	 * @return int
	 */
	public function count()
	{
		$query = $this->sql->selectCount();
		$values = $this->sql->getValues();

		$count = $this->conn->fetchCount($query, $values);
		$this->flush();

		return $count;
	}

	/**
	 * Remove todos os registros da busca
	 */
	public function delete()
	{
		$query = $this->sql->delete();
		$values = $this->sql->getValues();
		$this->conn->execute($query, $values);
		$this->flush();
	}

	/**
	 * Remove o registro pela PK
	 * @param int $id
	 */
	public function destroy($id)
	{
		$this->filterBy(static::PK, $id);

		$query = $this->sql->delete();
		$values = $this->sql->getValues();
		$this->conn->execute($query, $values);
		$this->flush();
	}

	/**
	 * Salva o registro no banco
	 * @param Model $model
	 */
	public function save(Model $model)
	{
		$model->validate();
		if ($this->exists($model)) {
			$this->update($model);
		} else {
			$this->insert($model);
		};
		$this->flush();

		return $model;
	}

	/**
	 * Remove todos os filtros e ordenação
	 */
	public function flush()
	{
		$this->sql = new Sql(static::TABLE);

		return $this;
	}

	/**
	 * @param Model $model
	 */
	private function insert($model)
	{
		$this->sql = new Sql(static::TABLE, $this->mapRow($model));
		$query = $this->sql->insert();
		$values = $this->sql->getValues();
		$this->conn->execute($query, $values);

		$model->id = (int) $this->conn->getLastInsertId();
	}

	/**
	 * @param Model $model
	 */
	private function update($model)
	{
		$this->sql = new Sql(static::TABLE, $this->mapRow($model));
		$this->filterBy(static::PK, $model->id);

		$query = $this->sql->update();
		$values = $this->sql->getValues();
		$this->conn->execute($query, $values);
		$this->flush();
	}

	/**
	 * Retorna TRUE se o model existir no banco
	 * @return bool
	 */
	protected function exists($model)
	{
		$orm = new static($this->conn);
		$orm->filterBy(static::PK, $model->id);

		return $orm->count() > 0;
	}

	/**
	 * Aplica um filtro ($comparator aceita mais de uma regra)
	 * @param string $comparator
	 * @param mixed $value
	 * @example filterBy('name = ? AND email = ?', 'John', 'john@email.com')
	 */
	public function filterBy($comparator, ...$values)
	{
		$this->sql->addWhere($comparator, ...$values);

		return $this;
	}

	/**
	 * Ordem por um campo
	 * @param string $column
	 * @param string $mode 'ASC' | 'DESC'
	 * @param int $priority
	 */
	public function sortBy($column, $mode = 'ASC', $priority = 0)
	{
		$this->sql->addOrderBy($column . ' ' . $mode, $priority);

		return $this;
	}

	public function sortNewest()
	{
		return $this->sortBy('id', 'DESC');
	}

	public function sortOldest()
	{
		return $this->sortBy('id', 'ASC');
	}

	public function sortRand()
	{
		return $this->sortBy('RAND()');
	}

	/**
	 * @param int $pageSize
	 * @param int $currentPage
	 */
	public function paginate($pageSize, $currentPage = 1)
	{
		$this->pagination = new Pagination($pageSize, $currentPage);
		return $this;
	}

	/**
	 * Define o limit com base na paginação
	 */
	private function setLimit()
	{
		$query = $this->sql->selectCount();
		$values = $this->sql->getValues();
		$count = $this->conn->fetchCount($query, $values);

		if ($count) {
			$this->pagination->setCount($count);
			$this->sql->setLimit($this->pagination->offset, $this->pagination->pageSize);
		}
	}

	public function beginTransaction()
	{
		$this->conn->getPdo()->beginTransaction();
	}

	/**
	 * Completa a Transação
	 */
	public function commit()
	{
		$this->conn->getPdo()->commit();
	}

	/**
	 * Cancela a Transação
	 */
	public function rollback()
	{
		$this->conn->getPdo()->rollBack();
	}
}
