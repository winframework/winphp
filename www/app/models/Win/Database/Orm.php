<?php

namespace Win\Database;

use Win\Database\Mysql\MysqlConnection;
use Win\Database\Orm\Model;
use Win\Database\Orm\Pagination;
use Win\Database\Orm\Traits\FilterTrait;
use Win\Database\Orm\Traits\PaginationTrait;
use Win\Database\Orm\Traits\RawTrait;
use Win\Database\Orm\Traits\SortTrait;
use Win\Database\Sql\Clauses\Where;
use Win\Database\Sql\Query;

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

	/** @var Model */
	protected $model;

	/** @var Query */
	protected $query;

	/** @var Connection */
	public $conn;

	/** @var bool */
	public $debug;

	/** @var Where */
	public $filter;

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
		$query->setStatement('SELECT');
		$query->limit->set(0, 1);
		$row = $this->conn->fetch($query, $query->getValues());

		return $this->mapModel($row);
	}

	/**
	 * Retorna o todos os resultados da busca
	 */
	public function list()
	{
		$this->setLimit();
		$query = $this->query;
		$query->setStatement('SELECT');
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
		$query->setStatement('SELECT COUNT');

		return $this->conn->fetchCount($query, $query->getValues());
	}

	/**
	 * Remove todos os registros da busca
	 * @return bool
	 */
	public function delete()
	{
		$query = $this->query;
		$query->setStatement('DELETE');

		return $this->conn->query($query, $query->getValues());
	}

	/**
	 * Remove o registro pela PK
	 * @param int $id
	 * @return bool
	 */
	public function destroy($id)
	{
		$query = $this->query;
		$this->filterBy('id', '=', $id);
		$query->setStatement('DELETE');

		return $this->conn->query($query, $query->getValues());
	}

	/**
	 * Salva o registro no banco
	 * @param Model $model
	 * @return bool
	 */
	public function save(Model $model)
	{
		$this->model = $model;

		return $this->insertOrUpdate();
	}

	/** @return bool */
	private function insertOrUpdate()
	{
		if (!$this->modelExists()) {
			$success = $this->insert();
		} else {
			$success = $this->update();
		}

		return $success;
	}

	/** @return bool */
	private function insert()
	{
		$query = $this->query;
		$query->values = $this->mapRow($this->model);
		$query->setStatement('INSERT');

		$success = $this->conn->query($query, $query->getValues());
		$this->model->setId((int) $this->conn->getLastInsertId());

		return $success;
	}

	/** @return bool */
	private function update()
	{
		$this->filterBy('Id', '=', $this->model->getId());
		$query = $this->query;
		$query->values = $this->mapRow($this->model);
		$query->setStatement('UPDATE');

		$success = $this->conn->query($query, $query->getValues());
		$this->query->where = new Where();

		return $success;
	}

	/** @return bool */
	protected function modelExists()
	{
		return $this->model->getId() > 0;
	}
}
