<?php

namespace Win\Database;

use Win\Database\Orm\Model;
use Win\Database\Orm\Traits\DebugTrait;
use Win\Database\Orm\Traits\OrderTrait;
use Win\Database\Orm\Traits\ReadTrait;
use Win\Database\Orm\Traits\WriteTrait;
use Win\Database\Sql\Queries\Select;
use Win\Singleton\SingletonTrait;

/**
 * Object Relational Mapping
 */
abstract class Orm implements RepositoryInterface
{
	use SingletonTrait {
		__construct as finalConstruct;
	}

	use OrderTrait;
	use DebugTrait;
	use ReadTrait;
	use WriteTrait;

	/** @var Connection */
	protected static $conn;

	/** @var string */
	protected $table;

	/** @var Model */
	protected $model;

	/** @var Select */
	protected $query;

	public function __construct()
	{
		$this->query = new Select($this);
	}

	/** @return Orm */
	protected function orm()
	{
		return $this;
	}

	/** @return Select */
	protected function getQuery()
	{
		return $this->query;
	}

	/** @param Connection $conn */
	public static function setConnection(Connection $conn)
	{
		static::$conn = $conn;
	}

	/** @return Connection */
	public function getConnection()
	{
		return static::$conn;
	}

	/**
	 * @param Model $model
	 * @return mixed[]
	 */
	abstract public function mapRow($model);

	/**
	 * @param mixed[] $row
	 * @return Model
	 */
	abstract public function mapModel($row);

	/** @return mixed[] */
	public function getRowValues()
	{
		return $this->mapRow($this->getModel());
	}

	/** @return string */
	public function getTable()
	{
		return $this->table;
	}

	/** @return Model */
	public function getModel()
	{
		return $this->model;
	}

	/** @return bool */
	public function modelExists()
	{
		return $this->model->getId() > 0;
	}
}
