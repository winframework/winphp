<?php

namespace Win\Database\Sql\Queries;

use Win\Database\RepositoryInterface;
use Win\Database\Sql\Query;

/**
 * UPDATE SET
 */
class Update extends Query
{
	/** @var mixed[] */
	protected $values;

	public function __construct(RepositoryInterface $repository)
	{
		parent::__construct($repository);
		$this->values = $repository->getRowValues();
	}

	/** @return string */
	public function toString()
	{
		$params = $this->getParams();

		return 'UPDATE ' . $this->table
				. ' SET ' . implode(', ', $params)
				. ' WHERE id = ? ';
	}

	/** @return string[] */
	protected function getParams()
	{
		$columns = array_keys($this->values);
		$params = [];
		foreach ($columns as $column) {
			$params[] = $column . ' = ?';
		}

		return $params;
	}

	/** @return mixed[] */
	public function getValues()
	{
		$values = array_values($this->values);
		$values[] = $this->repository->getModel()->getId();

		return $values;
	}

	/** @return bool */
	public function execute()
	{
		return $this->connection->query($this, $this->getValues());
	}
}
