<?php

namespace Win\Database;

use Win\Database\Orm\Traits\DebugTrait;
use Win\Database\Orm\Traits\OrderTrait;
use Win\Database\Orm\Traits\ReadTrait;
use Win\Database\Orm\Traits\WriteTrait;
use Win\Database\Sql\Queries\Select;
use Win\Singleton\SingletonTrait;

/**
 * Object Relational Mapping
 */
abstract class Orm
{
	/** @var string */
	protected $table;

	/** @var Connection */
	protected static $db;

	use SingletonTrait {
		__construct as finalConstruct;
	}

	use OrderTrait;
	use DebugTrait;
	use ReadTrait;
	use WriteTrait;

	/** @var Select */
	private $query;

	public function __construct()
	{
		$this->query = new Select($this);
	}

	/** @param Connection $db */
	public static function setConnection(Connection $db)
	{
		static::$db = $db;
	}

	/** @return Connection */
	public static function getConnection()
	{
		return static::$db;
	}

	/**
	 * @param mixed[] $row
	 * @return Model
	 */
	abstract public function mapModel($row);

	/**
	 * @param Model $model
	 * @return mixed[]
	 */
	abstract public function mapRow($model);

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
}
