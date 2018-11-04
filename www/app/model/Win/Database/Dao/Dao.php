<?php

namespace Win\Database\Dao;

use PDO;
use Win\Database\Connection\Mysql;
use Win\Database\Dao\Query\Select;
use Win\DesignPattern\SingletonTrait;

/**
 * Database Access Object
 */
abstract class Dao {

	protected $name = null;
	protected $table = null;

	use SingletonTrait {
		__construct as finalConstruct;
	}

	/** @var PDO */
	static protected $pdo = null;

	/** @var Select */
	private $select;

	public function __construct() {
		$this->flush();
	}

	private function flush() {
		$this->select = new Select($this->table);
	}

	/** @param string[] $row */
	abstract public function mapObject($row);

	/** @return PDO */
	public static function getPdo() {
		if (is_null(self::$pdo)) {
			self::$pdo = Mysql::instance()->getPdo();
		}
		return self::$pdo;
	}

	/** @param PDO $pdo */
	public static function setPdo(PDO $pdo) {
		self::$pdo = $pdo;
	}

	/**
	 * @param string $query
	 * @return mixed[]
	 */
	public static function select($query) {
		$result = static::getPdo()->query($query);
		$rows = [];
		while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
			$rows[] = $row;
		}
		return $rows;
	}

	/**
	 * @param string $query
	 * @return boolean
	 */
	public static function query($query) {
		return (boolean) static::getPdo()->exec($query);
	}

	/**
	 * @param string $query
	 * @return boolean
	 */
	public static function insert($query) {
		return static::query($query);
	}

	/**
	 * @param string $query
	 * @return boolean
	 */
	public static function update($query) {
		return static::query($query);
	}

	/**
	 * @param string $query
	 * @return boolean
	 */
	public static function delete($query) {
		return static::query($query);
	}

	/**
	 * Retorna um registro pelo id
	 * @param int $id
	 */
	public function find($id) {
		$this->select->where->add('id = ' . $id);
		return $this->first();
	}

	/**
	 * Adiciona filtros para busca
	 * @return self
	 */
	public function filter($filter, $values) {
		$this->select->where->add($filter, $values);
		return $this;
	}

	/**
	 * Ordena por
	 * @param string $orderBy
	 * @return self
	 */
	public function orderBy($orderBy) {
		$this->select->orderBy->set($orderBy);
		return $this;
	}

	/**
	 * Retorna todos os registro da query
	 */
	public function all() {
		$rows = static::select($this->select);
		$this->flush();
		$all = [];
		foreach ($rows as $row) {
			$all[] = $this->mapObject($row);
		}
		return $all;
	}

	public function latest() {
		$this->select->orderBy->set('id DESC');
		return $this->all();
	}

	/**
	 * Retorna o primeiro registro da query
	 * @return mixed[]
	 */
	public function first() {
		$rows = static::select($this->select);
		$this->flush();
		return $this->mapObject($rows[0]);
	}

	/**
	 * Retorna o último registro da query
	 * @return mixed[]
	 */
	public function last() {
		$rows = static::select($this->select);
		$this->flush();
		$row = end($rows);
		return $this->mapObject($row);
	}

}
