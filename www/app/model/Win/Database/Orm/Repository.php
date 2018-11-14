<?php

namespace Win\Database\Orm;

use Win\Database\Connection;
use Win\Database\Sql\Delete;
use Win\Database\Sql\Insert;
use Win\Database\Sql\Select;
use Win\Database\Sql\Update;
use Win\DesignPattern\SingletonTrait;

/**
 * Object Relational Mapping
 */
abstract class Repository {

	/** @var string */
	protected $table;

	/** @var Model */
	protected $model;

	/** @var boolean */
	protected $debug;

	/** @var Connection */
	protected static $db;

	use SingletonTrait {
		__construct as finalConstruct;
	}

	/** @var Select */
	private $query;

	public function __construct() {
		$this->query = new Select($this);
	}

	/** @param Connection $db */
	public static function setConnection(Connection $db) {
		static::$db = $db;
	}

	/** @return Connection */
	public static function getConnection() {
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
	public function getRowValues() {
		return $this->mapRow($this->getModel());
	}

	/** Liga o debug */
	public function debugOn() {
		$this->debug = true;
	}

	/** Desliga o debug */
	public function debugOff() {
		$this->debug = false;
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
		$rows = $this->query->execute();
		return $this->mapModel($rows[0]);
	}

	/**
	 * Retorna todos os resultado da consulta
	 * @return Model[]
	 */
	public function results() {
		$rows = $this->query->execute();
		$all = [];
		foreach ($rows as $row) {
			$all[] = $this->mapModel($row);
		}
		return $all;
	}

	/** @return int */
	public function numRows() {
		$count = $this->query->count();
		return $count;
	}

	/**
	 * Define as colunas do resultado
	 * @param string[] $collumns
	 */
	public function setCollumns($collumns) {
		$this->query->collumns = $collumns;
		return $this;
	}

	/**
	 * Adiciona uma coluna do resultado
	 * @param string $collumn
	 */
	public function addCollumn($collumn) {
		$this->query->collumns[] = $collumn;
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
		$success = $query->execute();
		$this->model->setId(static::$db->getLastInsertId());
		return $success;
	}

	/** @return boolean */
	public function update() {
		$query = new Update($this);
		return $query->execute();
	}

	/**
	 * Exlui o registro do banco
	 * @param Model $model
	 * @return boolean
	 */
	public function delete(Model $model) {
		$query = new Delete($this);
		$query->where->add('id', '=', $model->getId());
		return $query->execute();
	}

}
