<?php

namespace Win\Database;

use Win\Database\Connection;
use Win\Database\Sql\Select;
use Win\Singleton\SingletonTrait;

/**
 * Active Record Model
 */
abstract class ActiveRecord {

	protected static $model = null;
	protected static $table = null;

	/** @var Connection */
	protected static $connection = null;

	use SingletonTrait {
		__construct as finalConstruct;
	}

	/** @var Select */
	protected static $select;

	public function __construct() {
		
	}

	/** @return static */
	public static function records() {
		static::$select = new Select(static::$table);
		return static::instance();
	}

	/** @param Connection $connection */
	public static function setConnection(Connection $connection) {
		static::$connection = $connection;
	}

	/** @param string[] $row */
	abstract public function mapObject($row);

	/**
	 * Retorna um registro pelo id
	 * @param int $id
	 */
	public function find($id) {
		return $this->findBy('id', $id);
	}

	/**
	 * Retorna um registro pelo campo
	 * @param string $column
	 * @param mixed $value
	 */
	protected function findBy($column, $value) {
		$this->filter($column, '=', $value);
		return $this->first();
	}

	/**
	 * Adiciona filtros para busca
	 * @return self
	 */
	public function filter($column, $operator, $value) {
		static::$select->where->add($column, $operator, $value);
		return $this;
	}

	/**
	 * Ordena por
	 * @param string $orderBy
	 * @return self
	 */
	public function orderBy($orderBy) {
		static::$select->orderBy->set($orderBy);
		return $this;
	}

	/**
	 * Limita
	 * @param int $limit
	 * @return self
	 */
	public function limit($limit) {
		static::$select->limit->set($limit);
		return $this;
	}

	/** Limpa o select sql */
	private function flushSelect() {
		static::$select = new Select(static::$table);
	}

	/** @param string $columns */
	public function select($columns) {
		static::$select->columns = $columns;
	}

	/**
	 * Retorna todos os registro da query
	 * @return static
	 */
	public function all() {
		$rows = static::$connection->select(static::$select);
		$this->flushSelect();
		$all = [];
		foreach ($rows as $row) {
			$all[] = $this->mapObject($row);
		}
		return $all;
	}

	/** @return static */
	public function latest() {
		static::$select->orderBy->set('id DESC');
		return $this->all();
	}

	/** @return static */
	public function get() {
		return $this->first();
	}

	/** @return static */
	public function first() {
		$this->limit(1);
		$rows = static::$connection->select(static::$select);
		$this->flushSelect();
		return $this->mapObject($rows[0]);
	}

	/** @return static */
	public function last() {
		$rows = static::$connection->select(static::$select);
		$this->flushSelect();
		$row = end($rows);
		return $this->mapObject($row);
	}

}
