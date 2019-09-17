<?php

namespace Win\Database;

use PDOException;
use Win\Database\Mysql\MysqlConnection;
use Win\Database\Orm\ErrorEnum;
use Win\Database\Orm\Model;
use Win\Database\Orm\OrmException;
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

	/** @var string */
	const PK = 'Id';

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
		try {
			$query = $this->query;
			$query->setBuilder('SELECT');
			$query->limit->set(0, 1);
			$row = $this->conn->fetch($query, $query->getValues());

			return $this->mapModel($row);
		} catch (PDOException $e) {
			throw new OrmException(ErrorEnum::ON_FETCH, $e->getCode());
		}
	}

	/**
	 * Retorna o todos os resultados da busca
	 */
	public function list()
	{
		try {
			$this->setLimit();
			$query = $this->query;
			$query->setBuilder('SELECT');
			$rows = $this->conn->fetchAll($query, $query->getValues());

			return array_map([$this, 'mapModel'], $rows);
		} catch (PDOException $e) {
			throw new OrmException(ErrorEnum::ON_FETCH, $e->getCode());
		}
	}

	/**
	 * Retorna o total de resultados da busca
	 * O LIMIT (Paginação) não é aplicado
	 */
	public function count()
	{
		try {
			$query = $this->query;
			$query->setBuilder('SELECT COUNT');

			return $this->conn->fetchCount($query, $query->getValues());
		} catch (PDOException $e) {
			throw new OrmException(ErrorEnum::ON_FETCH, $e->getCode());
		}
	}

	/**
	 * Remove todos os registros da busca
	 * @return bool
	 */
	public function delete()
	{
		try {
			$query = $this->query;
			$query->setBuilder('DELETE');

			return $this->conn->query($query, $query->getValues());
		} catch (PDOException $e) {
			throw new OrmException(ErrorEnum::ON_DELETE, $e->getCode());
		}
	}

	/**
	 * Remove o registro pela PK
	 * @param int $id
	 * @return bool
	 */
	public function destroy($id)
	{
		try {
			$query = $this->query;
			$this->filterBy(static::PK, '=', $id);
			$query->setBuilder('DELETE');

			return $this->conn->query($query, $query->getValues());
		} catch (PDOException $e) {
			throw new OrmException(ErrorEnum::ON_DELETE, $e->getCode());
		}
	}

	/**
	 * Salva o registro no banco
	 * @param Model $model
	 * @return bool
	 */
	public function save(Model $model)
	{
		try {
			$this->model = $model;

			return $this->insertOrUpdate();
		} catch (PDOException $e) {
			throw new OrmException(ErrorEnum::ON_SAVE, $e->getCode());
		}
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
		$query->setBuilder('INSERT');

		$success = $this->conn->query($query, $query->getValues());
		$this->model->id = (int) $this->conn->getLastInsertId();

		return $success;
	}

	/** @return bool */
	private function update()
	{
		$this->query = new Query($this);
		$this->filterBy(static::PK, '=', $this->model->id);

		$query = $this->query;
		$query->values = $this->mapRow($this->model);
		$query->setBuilder('UPDATE');
		$success = $this->conn->query($query, $query->getValues());

		return $success;
	}

	/** @return bool */
	protected function modelExists()
	{
		return $this->model->id > 0;
	}
}
