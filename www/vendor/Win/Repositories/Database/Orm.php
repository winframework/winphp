<?php

namespace Win\Repositories\Database;

use Win\Application;
use Win\Common\Pagination;
use Win\Models\Model;
use Win\HttpException;

/**
 * Object Relational Mapping
 */
abstract class Orm
{
	const TABLE = '';
	const PK = 'id';

	public Connection $conn;
	public ?Pagination $pagination;
	protected Sql $sql;

	/**
	 * @param Model $model
	 * @return mixed[]
	 */
	abstract public static function mapRow($model);

	abstract public static function mapModel($row);

	/**
	 * @param Connection $connection
	 */
	public function __construct(?Connection $connection = null)
	{
		$this->conn = $connection ?: Application::app()->conn;
		$this->sql = new Sql(static::TABLE);
		$this->pagination = null;
	}

	/**
	 * Executa SQL
	 * @param string $query
	 * @param mixed[]
	 */
	protected function execute($query, ...$values)
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
		$values = $this->sql->values();
		$row = $this->conn->fetch($query, $values);
		$this->flush();

		if ($row !== false) {
			return $this->mapModel($row);
		}
	}

	/**
	 * Retorna o primeiro resultado da busca ou redireciona para Página 404
	 */
	public function oneOr404()
	{
		$model = $this->one();
		if (is_null($model)) {
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
		return $this->filter(static::PK, $id)->one();
	}

	/**
	 * Retorna o registro pela PK ou redireciona para Página 404
	 * @param int $id
	 */
	public function findOr404($id)
	{
		return $this->filter(static::PK, $id)->oneOr404();
	}

	/**
	 * Retorna o todos os resultados da busca
	 */
	public function list()
	{
		if (!is_null($this->pagination)) {
			$this->setLimit();
		}

		$query = $this->sql->select();
		$values = $this->sql->values();
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
		$values = $this->sql->values();

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
		$values = $this->sql->values();
		$this->conn->execute($query, $values);
		$this->flush();
	}

	/**
	 * Remove o registro pela PK
	 * @param int $id
	 */
	public function destroy($id)
	{
		$this->filter(static::PK, $id);

		$query = $this->sql->delete();
		$values = $this->sql->values();
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
		$this->sql = new Sql(static::TABLE, $this->mapRow($model));

		if ($this->exists($model->id)) {
			$this->filter(static::PK, $model->id);
			$query = $this->sql->update();
		} else {
			$query = $this->sql->insert();
		}
		$values = array_values($this->sql->values());
		$this->conn->execute($query, $values);
		$model->id = $model->id ?? $this->conn->lastInsertId();

		$this->flush();
		return $model;
	}

	/**
	 * Atualiza somente as colunas informadas
	 * @param mixed[] $columns
	 * @return int
	 */
	public function update($columns)
	{
		$this->sql->setValues($columns);
		$query = $this->sql->update();
		$values = array_values($this->sql->values());
		$stmt = $this->conn->stmt($query, $values);
		$this->flush();

		return $stmt->rowCount();
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
	 * Retorna TRUE se o model existir no banco
	 * @param int $id
	 * @return bool
	 */
	protected function exists($id)
	{
		$orm = new static($this->conn);
		return $orm->filter(static::PK, $id)->count() > 0;
	}

	/**
	 * Define as colunas da busca
	 * @param string $columns
	 */
	public function select(...$columns)
	{
		$this->sql->columns = $columns;

		return $this;
	}

	/**
	 * Adiciona (inner) join
	 * @param string $query
	 */
	public function join($query)
	{
		$this->sql->addJoin('JOIN ' . $query);

		return $this;
	}

	/**
	 * Adiciona left join
	 * @param string $query
	 */
	public function leftJoin($query)
	{
		$this->sql->addJoin('LEFT JOIN ' . $query);

		return $this;
	}

	/**
	 * Adiciona right join
	 * @param string $query
	 */
	public function rightJoin($query)
	{
		$this->sql->addJoin('RIGHT JOIN ' . $query);

		return $this;
	}

	/**
	 * Aplica filtros
	 * @param string $comparator
	 * @param mixed $values
	 * @example filter('name', 'John')
	 * @example filter('name = ? OR email = ?', 'John', 'email')
	 */
	public function filter($comparator, ...$values)
	{
		$this->sql->addWhere($comparator, $values);

		return $this;
	}

	/**
	 * Aplica filtros (aceita bind params)
	 * @param string $query
	 * @param mixed[] $values
	 * @example filterBy('name = :name OR age > 0', [':name' => 'John'])
	 */
	public function filterBy($query, $values = [])
	{
		$this->sql->addWhere($query, $values);

		return $this;
	}

	/**
	 * Ordem por um campo
	 * @param string $rule
	 * @param int $priority
	 */
	public function sort($rule, $priority = 0)
	{
		$this->sql->addOrderBy($rule, $priority);

		return $this;
	}

	/**
	 * Define um limite das buscas com list()
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
		$values = $this->sql->values();
		$count = $this->conn->fetchCount($query, $values);

		if ($count) {
			$this->pagination->setCount($count);
			$this->sql->setLimit($this->pagination->offset, $this->pagination->pageSize);
		}
	}

	/**
	 * Exibe a query e os valores para debug
	 * @param string $method
	 * @return array<string,mixed>
	 */
	public function debug($method = 'select')
	{
		return [$this->sql->$method(), $this->sql->values()];
	}
}
