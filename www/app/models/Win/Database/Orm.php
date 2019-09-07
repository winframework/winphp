<?php

namespace Win\Database;

use Win\Database\Orm\Model;
use Win\Database\Orm\Traits\ConnectableTrait;
use Win\Database\Orm\Traits\DebugTrait;
use Win\Database\Sql\Query;

/**
 * Object Relational Mapping
 */
abstract class Orm
{
	use ConnectableTrait;
	use DebugTrait;

	/** @var string */
	public $table;

	/** @var string */
	public $entity;

	/** @var Model */
	protected $model;

	/** @var Query */
	protected $query;

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

	/**
	 * @param Connection $connection
	 */
	public function __construct($connection = null)
	{
		$this->conn = $connection ?: self::$defaultConnection;
		$this->query = new Query($this);
	}

	/**
	 * Retorna o primeiro resultado da busca
	 */
	public function one()
	{
		$this->query->setStatement('SELECT');
		$row = $this->conn->fetch(
			$this->query,
			$this->query->getValues()
		);

		return $this->mapModel($row);
	}

	/**
	 * Retorna o registro pela PK
	 * @param int $id
	 */
	public function find($id)
	{
		$this->filterBy('id', '=', $id);

		return $this->one();
	}

	/**
	 * Retorna o todos os resultados da busca
	 */
	public function list()
	{
		$query = $this->query;
		$query->setStatement('SELECT');
		$rows = $this->conn->fetchAll($query, $query->getValues());

		return array_map([$this, 'mapModel'], $rows);
	}

	/**
	 * Retorna o total de resultados da busca
	 */
	public function count()
	{
		$query = $this->query;
		$query->setStatement('SELECT_COUNT');

		return $this->conn->fetchCount($query, $query->getValues());
	}

	/**
	 * Remove todos os registros da busca
	 * @return bool
	 */
	public function delete()
	{
		$query = $this->query;
		$query->setStatement('DELETE');

		return $this->conn->query($query, $query->getValues());
	}

	/**
	 * Remove o registro pela PK
	 * @param int $id
	 * @return bool
	 */
	public function destroy($id)
	{
		$query = $this->query;
		$this->filterBy('id', '=', $id);
		$query->setStatement('DELETE');

		return $this->conn->query($query, $query->getValues());
	}

	/**
	 * Salva o registro no banco
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
		$query = $this->query;
		$query->setStatement('INSERT');
		$query->setValues($this->mapRow($this->model));

		$success = $this->conn->query($query, $query->getValues());
		$this->model->setId((int) $this->conn->getLastInsertId());

		return $success;
	}

	/** @return bool */
	public function update()
	{
		$query = $this->query;
		$query->setStatement('UPDATE');
		$values = $this->mapRow($this->model);
		$this->query->setValues($values);

		return $this->conn->query($query, $query->getValues());
	}

	/** @return bool */
	public function modelExists()
	{
		return $this->model->getId() > 0;
	}

	/**
	 * Ordena por um campo
	 * @param string $column
	 * @param string $mode
	 * @return static
	 */
	public function sortBy($column, $mode = 'DESC')
	{
		$this->query->orderBy($column . ' ' . $mode);

		return $this;
	}
}
