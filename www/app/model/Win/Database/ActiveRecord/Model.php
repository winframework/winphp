<?php

namespace Win\Database\ActiveRecord;

use Win\Database\Connection;
use Win\Database\Sql\Select;
use Win\DesignPattern\SingletonTrait;

/**
 * Active Record Model
 */
abstract class Model {

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
	 * @param string $collumn
	 * @param mixed $value
	 */
	protected function findBy($collumn, $value) {
		$this->filter($collumn, '=', $value);
		return $this->first();
	}

	/**
	 * Adiciona filtros para busca
	 * @return self
	 */
	public function filter($collumn, $operator, $value) {
		static::$select->where->add($collumn, $operator, $value);
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

	/** @param string $collumns */
	public function select($collumns) {
		static::$select->collumns = $collumns;
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