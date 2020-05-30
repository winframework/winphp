<?php

namespace Win\Repositories\Database;

use Win\Repositories\Pagination;
use Win\Models\Model;
use Win\Repositories\Database\Orm\FilterTrait;
use Win\Repositories\Database\Orm\PaginationTrait;
use Win\Repositories\Database\Orm\RawTrait;
use Win\Repositories\Database\Orm\SortTrait;
use Win\Repositories\Database\Sql\Builder;
use Win\Repositories\Database\Sql\Query;
use Win\Request\HttpException;

/**
 * Object Relational Mapping
 */
abstract class Orm
{
	use FilterTrait;
	use SortTrait;
	use RawTrait;
	use PaginationTrait;

	/** @var string */
	const TABLE = '';

	/** @var string */
	const TITLE = '';

	/** @var string */
	const PK = 'Id';

	/** @var Connection */
	public $conn;

	/** @var bool */
	public $debug;

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
		$this->query = new Query($this);
		$this->pagination = new Pagination();
	}

	/**
	 * Retorna o primeiro resultado da busca
	 */
	public function one()
	{
		$query = $this->query->build(Builder::SELECT);
		$query->limit->set(0, 1);
		$row = $this->conn->fetch($query, $query->getValues());
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
		$query = $this->query->build(Builder::SELECT);
		$rows = $this->conn->fetchAll($query, $query->getValues());
		$this->flush();

		return array_map([$this, 'mapModel'], $rows);
	}

	/**
	 * Retorna o total de resultados da busca
	 * Sem aplicar LIMIT e FLUSH
	 */
	public function count()
	{
		$query = $this->query->build(Builder::SELECT_COUNT);

		return $this->conn->fetchCount($query, $query->getValues());
	}

	/**
	 * Remove todos os registros da busca
	 */
	public function delete()
	{
		$query = $this->query->build(Builder::DELETE);

		$this->conn->query($query, $query->getValues());
		$this->flush();
	}

	/**
	 * Remove o registro pela PK
	 * @param int $id
	 */
	public function destroy($id)
	{
		$query = $this->query->build(Builder::DELETE);
		$this->filterBy(static::PK, $id);

		$this->conn->query($query, $query->getValues());
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
	 * Habilita o debug
	 */
	public function debug()
	{
		$this->debug = true;

		return $this;
	}

	/**
	 * Remove todos os filtros
	 */
	public function flush()
	{
		$this->query = new Query($this);

		return $this;
	}

	private function insert()
	{
		$query = $this->query->build(Builder::INSERT);
		$query->values = $this->mapRow($this->model);

		$this->conn->query($query, $query->getValues());
		$this->model->id = (int) $this->conn->getLastInsertId();
	}

	private function update()
	{
		$this->query = new Query($this);
		$this->filterBy(static::PK, $this->model->id);

		$query = $this->query->build(Builder::UPDATE);
		$query->values = $this->mapRow($this->model);

		$this->conn->query($query, $query->getValues());
		$this->flush();
	}

	/** @return bool */
	protected function modelExists()
	{
		$orm = new static($this->conn);
		$total = $orm->filterBy(static::PK, $this->model->id)->count();

		return $total > 0;
	}
}
