<?php

namespace Win\Database\Orm\Traits;

use Win\Database\Connection;
use Win\Database\Orm\Model;
use Win\Database\RepositoryInterface;
use Win\Database\Sql\Queries\Delete;
use Win\Database\Sql\Queries\Insert;
use Win\Database\Sql\Queries\Update;

/**
 * Comportamento de Escrever no banco
 */
trait WriteTrait
{
	/** @return Connection */
	abstract protected function getConnection();

	/** @return Model */
	abstract public function getModel();

	/** @param Model $model */
	abstract protected function setModel(Model $model);

	/** @return bool */
	abstract public function modelExists();

	/** @return RepositoryInterface */
	abstract public function orm();

	/**
	 * @param Model $model
	 * @return bool
	 */
	public function save(Model $model)
	{
		$this->setModel($model);

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
		$query = new Insert($this->orm());
		$success = $query->execute();
		$id = (int) $this->getConnection()->getLastInsertId();
		$this->getModel()->setId($id);

		return $success;
	}

	/** @return bool */
	public function update()
	{
		$query = new Update($this->orm());

		return $query->execute();
	}

	/**
	 * Remove o registro do banco
	 * @param Model $model
	 * @return bool
	 */
	public function delete(Model $model)
	{
		$query = new Delete($this->orm());
		$query->where()->add('id', '=', $model->getId());

		return $query->execute();
	}
}
