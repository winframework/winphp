<?php

namespace Win\Repositories\Database\Sql;

use Win\Repositories\Database\Orm;

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
		return $this->values + $this->where->values;
	}

	/**
	 * Retorna o comando SQL
	 * @return string
	 */
	public function __toString()
	{
		if ($this->orm->debug) {
			print_r('<pre>' . $this . '<br/>');
			print_r($this->getValues());
			print_r('</pre>');
		}

		return (string) $this;
	}

	/**
	 * SELECT * FROM
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
	 * 
	 * @return string
	 */
	public function insert()
	{
		return 'INSERT INTO ' . $this->table
			. ' (' . implode(',', array_keys($this->values)) . ')'
			. ' VALUES (' . implode(', ', $this->getBindParams()) . ')';
	}

	/**
	 * @return string[]
	 * @example return ['?','?','?']
	 */
	protected function getBindParams()
	{
		return str_split(str_repeat('?', count($this->values)));
	}

	/**
	 * @return string
	 */
	public function update()
	{
		return 'UPDATE ' . $this->table
			. ' SET ' . $this->updateSet()
			. $this->where
			. $this->limit;
	}

	/**
	 * @return string
	 */
	protected function updateSet()
	{
		return implode(', ', array_map(function ($column) {
			return $column . ' = ?';
		}, array_keys($this->values)));
	}

	/**
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
