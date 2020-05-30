<?php

namespace Win\Repositories\Database;

use Win\Common\Pagination;
use Win\Models\Model;

/**
 * Object Relational Mapping
 */
abstract class Orm
{
	/** @var string */
	const TABLE = '';

	/** @var string */
	const TITLE = '';

	/** @var string */
	const PK = 'id';

	/** @var Connection */
	public $conn;

	/** @var Pagination */
	public $pagination;

	/** @var Query */
	protected $query;

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
		$this->query = new Query(static::TABLE);
		$this->pagination = new Pagination();
	}

	/**
	 * Prepara raw Sql
	 * @param string $query
	 * @param mixed[]
	 */
	public function raw($query, $values = [])
	{
		$this->query = new Query(static::TABLE, $values, $query);

		return $this;
	}

	/**
	 * Executa SQL
	 * @param string $query
	 * @param mixed[]
	 */
	public function execute($query, $values = [])
	{
		return $this->conn->execute($query, $values);
	}

	/**
	 * Retorna o primeiro resultado da busca
	 */
	public function one()
	{
		$this->query->setLimit(0, 1);

		$query = $this->query->select();
		$values = $this->query->getValues();
		$row = $this->conn->fetch($query, $values);
		$this->flush();

		return $this->mapModel($row);
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
	 * Retorna o todos os resultados da busca
	 */
	public function list()
	{
		$this->applyPagination();

		$query = $this->query->select();
		$values = $this->query->getValues();
		$rows = $this->conn->fetchAll($query, $values);
		$this->flush();

		return array_map([$this, 'mapModel'], $rows);
	}

	/**
	 * Retorna o total de resultados da busca
	 * Sem aplicar LIMIT e FLUSH
	 */
	public function count()
	{
		$query = $this->query->selectCount();
		$values = $this->query->getValues();

		return $this->conn->fetchCount($query, $values);
	}

	/**
	 * Remove todos os registros da busca
	 */
	public function delete()
	{
		$query = $this->query->delete();
		$values = $this->query->getValues();
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

		$query = $this->query->delete();
		$values = $this->query->getValues();
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
	 * Remove todos os filtros, ordenação, etc
	 */
	public function flush()
	{
		$this->query = new Query(static::TABLE);

		return $this;
	}

	/**
	 * @param Model $model
	 */
	private function insert($model)
	{
		$this->query = new Query(static::TABLE, $this->mapRow($model));
		$query = $this->query->insert();
		$values = $this->query->getValues();
		$this->conn->execute($query, $values);

		$model->id = (int) $this->conn->getLastInsertId();
	}

	/**
	 * @param Model $model
	 */
	private function update($model)
	{
		$this->query = new Query(static::TABLE, $this->mapRow($model));
		$this->filterBy(static::PK, $model->id);

		$query = $this->query->update();
		$values = $this->query->getValues();
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
		$this->query->addWhere($comparator, ...$values);

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
		$this->query->addOrderBy($column . ' ' . $mode, $priority);

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
	 * @param int $pageNumber
	 */
	public function paginate($pageSize, $pageNumber = 1)
	{
		$this->pagination->setPage($pageSize, $pageNumber);

		return $this;
	}

	/**
	 * Define a paginação se necessário
	 */
	private function applyPagination()
	{
		$count = $this->count();
		$pagination = $this->pagination;

		if ($pagination->pageSize() && $count) {
			$pagination->setCount($count);
			$this->query->setLimit($pagination->offset(), $pagination->pageSize());
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
