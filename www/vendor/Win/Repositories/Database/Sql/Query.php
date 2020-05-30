<?php

namespace Win\Repositories\Database\Sql;

/**
 * SELECT, UPDATE, DELETE, etc
 */
class Query
{
	/** @var string */
	private $table;

	/** @var string */
	private $raw = null;

	/** @var array */
	private $values = [];

	/** @var Where */
	public $where;

	/** @var OrderBy */
	public $orderBy;

	/** @var Limit */
	public $limit;

	/**
	 * Prepara a query
	 * @param string $table
	 * @param mixed $values
	 * @param string $raw
	 */
	public function __construct($table, $values = [], $raw = null)
	{
		$this->table = $table;
		$this->values = $values;
		$this->raw = $raw;

		$this->where = new Where();
		$this->orderBy = new OrderBy();
		$this->limit = new Limit();
	}

	/** @return mixed[] */
	public function getValues()
	{
		return array_values($this->values + $this->where->values);
	}

	/**
	 * SELECT * FROM ...
	 * @return string
	 */
	public function select()
	{
		return ($this->raw ??
			'SELECT * FROM ' . $this->table)
			. $this->where
			. $this->orderBy
			. $this->limit;
	}

	/**
	 * SELECT COUNT(*) FROM
	 * @return string
	 */
	public function selectCount()
	{
		return ($this->raw ??
			'SELECT COUNT(*) FROM ' . $this->table)
			. $this->where;
	}

	/**
	 * INSERT INTO ... VALUES
	 * @return string
	 */
	public function insert()
	{
		$params = str_split(str_repeat('?', count($this->values)));
		return 'INSERT INTO ' . $this->table
			. ' (' . implode(',', array_keys($this->values)) . ')'
			. ' VALUES (' . implode(', ', $params) . ')';
	}

	/**
	 * UPDATE ... SET
	 * @return string
	 */
	public function update()
	{
		$columns = array_map(function ($column) {
			return $column . ' = ?';
		}, array_keys($this->values));

		return 'UPDATE ' . $this->table
			. ' SET ' . implode(', ', $columns)
			. $this->where
			. $this->limit;
	}

	/**
	 * DELETE FROM ...
	 * @return string
	 */
	public function delete()
	{
		return 'DELETE FROM ' . $this->table
			. $this->where
			. $this->limit;
	}

	/**
	 * @return string
	 */
	public function raw()
	{
		return $this->raw
			. $this->where
			. $this->limit;
	}
}
