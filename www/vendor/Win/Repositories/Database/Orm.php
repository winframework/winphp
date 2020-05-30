<?php

namespace Win\Repositories\Database;

use Win\Repositories\Pagination;
use Win\Models\Model;
use Win\Repositories\Database\Orm\FilterTrait;
use Win\Repositories\Database\Orm\PaginationTrait;
use Win\Repositories\Database\Orm\SortTrait;
use Win\Repositories\Database\Sql\Query;

/**
 * Object Relational Mapping
 */
abstract class Orm
{
	use FilterTrait;
	use SortTrait;
	use PaginationTrait;

	/** @var string */
	const TABLE = '';

	/** @var string */
	const TITLE = '';

	/** @var string */
	const PK = 'id';

	/** @var Connection */
	public $conn;

	/** @var Model */
	protected $model;

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
	public function rawQuery($query, $values = [])
	{
		$this->query = new Query(static::TABLE, $values, $query);

		return $this;
	}

	/**
	 * Rota do raw sql
	 */
	public function run()
	{
		$query = $this->query->raw();
		$values = $this->query->getValues();
		return $this->conn->execute($query, $values);
	}

	/**
	 * Retorna o primeiro resultado da busca
	 */
	public function one()
	{
		$this->query->limit->set(0, 1);

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
		$this->model = $model;
		!$this->modelExists() ? $this->insert() : $this->update();

		$this->flush();

		return $this->model;
	}

	/**
	 * Remove todos os filtros, ordenação, etc
	 */
	public function flush()
	{
		$this->query = new Query(static::TABLE);

		return $this;
	}

	private function insert()
	{
		$this->query = new Query(static::TABLE, $this->mapRow($this->model));
		$query = $this->query->insert();
		$values = $this->query->getValues();
		$this->conn->execute($query, $values);

		$this->model->id = (int) $this->conn->getLastInsertId();
	}

	private function update()
	{
		$this->query = new Query(static::TABLE, $this->mapRow($this->model));
		$this->filterBy(static::PK, $this->model->id);

		$query = $this->query->update();
		$values = $this->query->getValues();
		$this->conn->execute($query, $values);
		$this->flush();
	}

	/** @return bool */
	protected function modelExists()
	{
		$orm = new static($this->conn);
		$orm->filterBy(static::PK, $this->model->id);

		return $orm->count() > 0;
	}
}
