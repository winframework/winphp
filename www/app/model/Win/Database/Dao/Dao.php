<?php

namespace Win\Database\Dao;

use Win\Database\Connection;
use Win\Database\Connection\Mysql;
use Win\Database\Sql\Select;
use Win\DesignPattern\SingletonTrait;

/**
 * Database Access Object
 */
abstract class Dao {

	protected $name = null;
	protected $table = null;

	/** @var Connection */
	protected $connection = null;

	use SingletonTrait {
		__construct as finalConstruct;
	}

	/** @var Select */
	private $select;

	public function __construct() {
		$this->flushSelect();
		$this->connection = Mysql::instance();
	}

	/** @param Connection $connection */
	public function setConnection(Connection $connection) {
		$this->connection = $connection;
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
		$this->select->where->add($collumn, $operator, $value);
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

	/** Limpa o select sql */
	private function flushSelect() {
		$this->select = new Select($this->table);
	}

	/** @param string $collumns */
	public function select($collumns) {
		$this->select->collumns = $collumns;
	}

	/** Retorna todos os registro da query */
	public function all() {
		$rows = $this->connection->select($this->select);
		$this->flushSelect();
		$all = [];
		foreach ($rows as $row) {
			$all[] = $this->mapObject($row);
		}
		return $all;
	}

	/** Retorna todos por ordem decrescente */
	public function latest() {
		$this->select->orderBy->set('id DESC');
		return $this->all();
	}

	/** Retorna o primeiro registro da query */
	public function first() {
		$rows = $this->connection->select($this->select);
		$this->flushSelect();
		return $this->mapObject($rows[0]);
	}

	/** Retorna o último registro da query */
	public function last() {
		$rows = $this->connection->select($this->select);
		$this->flushSelect();
		$row = end($rows);
		return $this->mapObject($row);
	}

}
