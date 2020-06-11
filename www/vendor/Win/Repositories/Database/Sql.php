<?php

namespace Win\Repositories\Database;

/**
 * Cria comandos SQL
 */
class Sql
{
	/** @var string */
	private $table;

	/** @var string */
	private $raw = null;

	/** @var array */
	private $values = [];

	/** @var array */
	private $where = [];

	/** @var array */
	private $whereValues = [];

	/** @var array */
	private $orderBy = [];

	/** @var string */
	private $limit = '';

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
	}

	/** @return mixed[] */
	public function getValues()
	{
		return array_values($this->values + $this->whereValues);
	}

	/**
	 * SELECT * FROM ...
	 * @return string
	 */
	public function select()
	{
		return ($this->raw ?? 'SELECT * FROM ' . $this->table)
			. $this->where()
			. $this->orderBy()
			. $this->limit();
	}

	/**
	 * SELECT COUNT(*) FROM
	 * @return string
	 */
	public function selectCount()
	{
		return ($this->raw ?? 'SELECT COUNT(*) FROM ' . $this->table)
			. $this->where();
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
			. $this->where()
			. $this->limit();
	}

	/**
	 * DELETE FROM ...
	 * @return string
	 */
	public function delete()
	{
		return 'DELETE FROM ' . $this->table
			. $this->where()
			. $this->limit();
	}

	/**
	 * @return string
	 */
	public function raw()
	{
		return $this->raw
			. $this->where()
			. $this->limit();
	}

	/**
	 * WHERE ...
	 * @param string $comparator
	 * @param mixed $values
	 */
	public function addWhere($comparator, ...$values)
	{
		$this->whereValues = array_merge($this->whereValues, $values);
		if (count($values) && strpos($comparator, '?') === false) {
			$comparator .= ' = ?';
		}
		$this->where[] = '(' . $comparator . ')';
	}

	/**
	 * Define o limit
	 * @param int $offset
	 * @param int $limit
	 */
	public function setLimit($offset, $limit)
	{
		$this->limit = $offset . ',' . $limit;
	}

	/**
	 * Retorna o SQL
	 * @return string
	 */
	private function where()
	{
		if (empty($this->where)) {
			return '';
		}
		return ' WHERE ' . implode(' AND ', $this->where);
	}


	/**
	 * Define a ordenação principal
	 * @param string $orderBy
	 */
	public function setOrderBy($orderBy)
	{
		$this->orderBy = [$orderBy];
	}

	/**
	 * Adiciona uma ordenação
	 * @param string $orderBy
	 * @param int $priority
	 */
	public function addOrderBy($orderBy, $priority = 0)
	{
		$this->orderBy[$priority] = $orderBy;
	}

	/**
	 * LIMIT ...
	 * @return string
	 */
	private function limit()
	{
		if ($this->limit) {
			return ' LIMIT ' . $this->limit;
		}

		return '';
	}


	/**
	 * ORDER BY...
	 * @return string
	 */
	private function orderBy()
	{
		if (empty($this->orderBy)) {
			return '';
		}

		ksort($this->orderBy);
		return ' ORDER BY ' . implode(', ', $this->orderBy);
	}
}
