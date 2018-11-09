<?php

namespace Win\Database\Dao;

use Win\Database\Connection;
use Win\Database\Sql\Select;
use Win\DesignPattern\SingletonTrait;

/**
 * Database Access Object
 */
abstract class Dao {

	protected $model = null;
	protected $table = null;

	/** @var Connection */
	protected static $connection = null;

	use SingletonTrait {
		__construct as finalConstruct;
	}

	/** @var Select */
	private $query;

	public function __construct() {
		$this->flushQuery();
	}

	/** @param Connection $connection */
	public static function setConnection(Connection $connection) {
		static::$connection = $connection;
	}

	/** @param string[] $row */
	abstract public function mapObject($row);

	/** Limpa o select sql */
	private function flushQuery() {
		$this->query = new Select($this->table);
	}

	/**
	 * Retorna o primeiro resultado da consulta
	 * @return object
	 */
	public function result() {
		$rows = static::$connection->select($this->query);
		$this->flushQuery();
		return $this->mapObject($rows[0]);
	}

	/**
	 * Retorna todos os resultado da consulta
	 * return array
	 */
	public function results() {
		$rows = static::$connection->select($this->query);
		$this->flushQuery();
		$all = [];
		foreach ($rows as $row) {
			$all[] = $this->mapObject($row);
		}
		return $all;
	}

	/**
	 * Define as colunas do resultado
	 * @param string $collumns
	 */
	public function select($collumns) {
		$this->query->collumns = $collumns;
		return $this;
	}

	/**
	 * Filtra pelo id
	 * @param int $id
	 */
	public function find($id) {
		$this->filterBy('id', $id);
		return $this;
	}

	/**
	 * Filtra pelo campo
	 * @param string $collumn
	 * @param mixed $value
	 */
	public function filterBy($collumn, $value) {
		$this->filter($collumn, '=', $value);
		return $this;
	}

	/**
	 * Adiciona filtros para busca
	 */
	public function filter($collumn, $operator, $value) {
		$this->query->where->add($collumn, $operator, $value);
		return $this;
	}

	/**
	 * Ordena por um campo
	 * @param string $orderBy
	 */
	public function orderBy($orderBy) {
		$this->query->orderBy->set($orderBy);
		return $this;
	}

	/**
	 * Limita os resultados
	 * @param int $limit
	 */
	public function limit($limit) {
		$this->query->limit->set($limit);
		return $this;
	}

	/** Ordena pelos mais novos */
	public function newer() {
		$this->query->orderBy->set('id DESC');
		return $this;
	}

	/** Ordena pelos mais antigos */
	public function older() {
		$this->query->orderBy->set('id ASC');
		return $this;
	}

}
