<?php

namespace Win\Repositories\Database;

use Win\Models\Model;
use Win\Common\Pagination;
use Win\Repositories\Database\Orm\FilterTrait;
use Win\Repositories\Database\Orm\PaginationTrait;
use Win\Repositories\Database\Orm\RawTrait;
use Win\Repositories\Database\Orm\SortTrait;
use Win\Repositories\Database\Sql\Query;
use Win\Repositories\Database\Sql\Where;

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

	/** @var Where */
	public $filter;

	/** @var Model */
	protected $model;

	/** @var Query */
	protected $query;

	/**
	 * @param Model $model
	 * @return mixed[]
	 */
	abstract public static function mapRow($model);

	/**
	 * @param mixed[] $row
	 * @return Model
	 */
	abstract public static function mapModel($row);

	/**
	 * @param Connection $connection
	 */
	public function __construct($connection = null)
	{
		$this->conn = $connection ?: MysqlConnection::instance();
		$this->query = new Query($this);
		$this->filter = $this->query->where;
		$this->pagination = new Pagination();
	}

	/**
	 * Retorna o primeiro resultado da busca
	 */
	public function one()
	{
		$query = $this->query;
		$query->setBuilder('SELECT');
		$query->limit->set(0, 1);
		$row = $this->conn->fetch($query, $query->getValues());

		return $this->mapModel($row);
	}

	/**
	 * Retorna o todos os resultados da busca
	 */
	public function list()
	{
		$this->applyPagination();
		$query = $this->query;
		$query->setBuilder('SELECT');
		$rows = $this->conn->fetchAll($query, $query->getValues());

		return array_map([$this, 'mapModel'], $rows);
	}

	/**
	 * Retorna o total de resultados da busca
	 * O LIMIT (Paginação) não é aplicado
	 */
	public function count()
	{
		$query = $this->query;
		$query->setBuilder('SELECT COUNT');

		return $this->conn->fetchCount($query, $query->getValues());
	}

	/**
	 * Remove todos os registros da busca
	 */
	public function delete()
	{
		$query = $this->query;
		$query->setBuilder('DELETE');

		$this->conn->query($query, $query->getValues());
	}

	/**
	 * Remove o registro pela PK
	 * @param int $id
	 */
	public function destroy($id)
	{
		$query = $this->query;
		$this->filterBy(static::PK, $id);
		$query->setBuilder('DELETE');

		$this->conn->query($query, $query->getValues());
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
		$query = $this->query;
		$query->values = $this->mapRow($this->model);
		$query->setBuilder('INSERT');

		$this->conn->query($query, $query->getValues());
		$this->model->id = (int) $this->conn->getLastInsertId();
	}

	private function update()
	{
		$this->query = new Query($this);
		$this->filterBy(static::PK, $this->model->id);

		$query = $this->query;
		$query->values = $this->mapRow($this->model);
		$query->setBuilder('UPDATE');

		$this->conn->query($query, $query->getValues());
	}

	/** @return bool */
	protected function modelExists()
	{
		$orm = new static($this->conn);
		$total = $orm->filterBy(static::PK, $this->model->id)->count();

		return $total > 0;
	}
}
