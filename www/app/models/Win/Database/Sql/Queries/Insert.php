<?php

namespace Win\Database\Sql\Queries;

use Win\Database\Orm;
use Win\Database\Sql\Query;

/**
 * INSERT INTO
 */
class Insert extends Query
{
	/** @var mixed[] */
	protected $values;

	public function __construct(Orm $orm)
	{
		parent::__construct($orm);
		$this->values = $orm->getRowValues();
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
		return $this->connection->query($this, $this->getValues());
	}
}