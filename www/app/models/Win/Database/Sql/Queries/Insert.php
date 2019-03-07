<?php

namespace Win\Database\Sql\Queries;

use Win\Database\RepositoryInterface;
use Win\Database\Sql\Query;

/**
 * INSERT INTO
 */
class Insert extends Query
{
	/** @var mixed[] */
	protected $values;

	/**
	 * Prepara um insert
	 * @param RepositoryInterface $repository
	 */
	public function __construct(RepositoryInterface $repository)
	{
		parent::__construct($repository);
		$this->values = $repository->getRowValues();
	}

	/** @return string */
	public function toString()
	{
		$columns = array_keys($this->values);
		$params = $this->getParams();

		return 'INSERT INTO ' .
				$this->table .
				' (' . implode(',', $columns) . ')' .
				' VALUES (' . implode(', ', $params) . ')';
	}

	/**
	 * @return string[]
	 * @example return ['?','?','?']
	 */
	protected function getParams()
	{
		return str_split(str_repeat('?', count($this->values)));
	}

	/** @return mixed[] */
	public function getValues()
	{
		return array_values($this->values);
	}

	/** @return bool */
	public function execute()
	{
		return $this->conn->query($this, $this->getValues());
	}
}
