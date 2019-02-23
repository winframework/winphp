<?php

namespace Win\Database;

use Win\Contracts\Database\Orm\Model;
use Win\Database\Sql\Delete;
use Win\Database\Sql\Insert;
use Win\Database\Sql\Select;
use Win\Database\Sql\Update;
use Win\Singleton\SingletonTrait;

/**
 * Object Relational Mapping
 */
abstract class Orm
{
	/** @var string */
	protected $table;

	/** @var Model */
	protected $model;

	/** @var bool */
	protected $debug;

	/** @var Connection */
	protected static $db;

	use SingletonTrait {
		__construct as finalConstruct;
	}

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

	/** Liga o debug */
	public function debugOn()
	{
		$this->debug = true;
	}

	/** Desliga o debug */
	public function debugOff()
	{
		$this->debug = false;
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
	public function getDebugMode()
	{
		return $this->debug;
	}

	/** @return bool */
	public function modelExists()
	{
		return $this->model->getId() > 0;
	}

	/**
	 * Retorna o primeiro resultado da consulta
	 */
	public function one()
	{
		$rows = $this->query->execute();

		return $this->mapModel($rows[0]);
	}

	/**
	 * Retorna todos os resultado da consulta
	 */
	public function all()
	{
		$rows = $this->query->execute();
		$all = [];
		foreach ($rows as $row) {
			$all[] = $this->mapModel($row);
		}

		return $all;
	}

	/** @return int */
	public function numRows()
	{
		$count = $this->query->count();

		return $count;
	}

	/**
	 * Define as colunas do resultado
	 * @param string[] $columns
	 * @return static
	 */
	public function setColumns($columns)
	{
		$this->query->columns = $columns;

		return $this;
	}

	/**
	 * Adiciona uma coluna do resultado
	 * @param string $column
	 * @return static
	 */
	public function addColumn($column)
	{
		$this->query->columns[] = $column;

		return $this;
	}

	/**
	 * Filtra pelo id
	 * @param int $id
	 * @return static
	 */
	public function find($id)
	{
		$this->filterBy('id', $id);

		return $this;
	}

	/**
	 * Filtra pelo campo
	 * @param string $column
	 * @param mixed $value
	 * @return static
	 */
	public function filterBy($column, $value)
	{
		$this->filter($column, '=', $value);

		return $this;
	}

	/**
	 * Adiciona filtros para busca
	 * @return static
	 */
	public function filter($column, $operator, $value)
	{
		$this->query->where->add($column, $operator, $value);

		return $this;
	}

	/**
	 * Ordena por um campo
	 * @param string $orderBy
	 * @return static
	 */
	public function orderBy($orderBy)
	{
		$this->query->orderBy->set($orderBy);

		return $this;
	}

	/**
	 * Limita os resultados
	 * @param int $limit
	 * @return static
	 */
	public function limit($limit)
	{
		$this->query->limit->set($limit);

		return $this;
	}

	/**
	 * Ordena pelos mais novos
	 * @return static
	 */
	public function newer()
	{
		$this->orderBy('id DESC');

		return $this;
	}

	/**
	 * Ordena pelos mais antigos
	 * @return static
	 */
	public function older()
	{
		$this->orderBy('id ASC');

		return $this;
	}

	/**
	 * @param Model $model
	 * @return bool
	 */
	public function save(Model $model)
	{
		$this->model = $model;

		return $this->insertOrUpdate();
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
		$query = new Insert($this);
		$success = $query->execute();
		$this->model->setId((int) static::$db->getLastInsertId());

		return $success;
	}

	/** @return bool */
	public function update()
	{
		$query = new Update($this);

		return $query->execute();
	}

	/**
	 * Remove o registro do banco
	 * @param Model $model
	 * @return bool
	 */
	public function delete(Model $model)
	{
		$query = new Delete($this);
		$query->where->add('id', '=', $model->getId());

		return $query->execute();
	}
}
