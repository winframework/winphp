<?php

namespace Win\Database\Orm;

use Win\Database\Connection;
use Win\Database\Sql\Query\Delete;
use Win\Database\Sql\Query\Insert;
use Win\Database\Sql\Query\Select;
use Win\Database\Sql\Query\Update;
use Win\DesignPattern\SingletonTrait;

/**
 * Object Relational Mapping
 */
abstract class Repository {

	/** @var string */
	protected $table = null;

	/** @var Model */
	protected $model = null;

	/** @var boolean */
	protected $debug = false;

	/** @var Connection */
	protected static $db = null;

	use SingletonTrait {
		__construct as finalConstruct;
	}

	/** @var Select */
	private $query;

	public function __construct() {
		$this->flushQuery();
	}

	/** @param Connection $db */
	public static function setConnection(Connection $db) {
		static::$db = $db;
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

	/** Limpa o select sql */
	private function flushQuery() {
		$this->query = new Select($this);
	}

	/** @param boolean $debug */
	public function debug($debug = true) {
		$this->debug = $debug;
	}

	/** @return string */
	public function getTable() {
		return $this->table;
	}

	/** @return Model */
	public function getModel() {
		return $this->model;
	}

	/** @return boolean */
	public function getDebugMode() {
		return $this->debug;
	}

	/** @return boolean */
	public function modelExists() {
		return ($this->model->getId() > 0);
	}

	/**
	 * Retorna o primeiro resultado da consulta
	 * @return Model
	 */
	public function result() {
		$rows = static::$db->select($this->query);
		$this->flushQuery();
		return $this->mapModel($rows[0]);
	}

	/**
	 * Retorna todos os resultado da consulta
	 * @return Model[]
	 */
	public function results() {
		$rows = static::$db->select($this->query);
		$this->flushQuery();
		$all = [];
		foreach ($rows as $row) {
			$all[] = $this->mapModel($row);
		}
		return $all;
	}

	/**
	 * Define as colunas do resultado
	 * @param string $collumns
	 */
	public function collumns($collumns) {
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
		$this->orderBy('id DESC');
		return $this;
	}

	/** Ordena pelos mais antigos */
	public function older() {
		$this->orderBy('id ASC');
		return $this;
	}

	/**
	 * @param Model $model
	 * @return boolean
	 */
	public function save(Model $model) {
		$this->model = $model;
		return $this->insertOrUpdate();
	}

	/** @return boolean */
	private function insertOrUpdate() {
		if (!$this->modelExists()) {
			$success = $this->insert();
		} else {
			$success = $this->update();
		}
		return $success;
	}

	/** @return boolean */
	private function insert() {
		$query = new Insert($this);
		$success = static::$db->insert($query, $query->getValues());
		$this->model->setId(static::$db->getLastInsertId());
		return $success;
	}

	/** @return boolean */
	public function update() {
		$mapRow = $this->mapRow($this->model);
		$query = new Update($this, $mapRow);
		return static::$db->insert($query, $query->getValues());
	}

	/**
	 * Exlui o registro do banco
	 * @param Model $model
	 * @return boolean
	 */
	public function delete(Model $model) {
		$query = new Delete($this);
		$query->where->add('id', '=', $model->getId());
		return static::$db->delete($query);
	}

}
