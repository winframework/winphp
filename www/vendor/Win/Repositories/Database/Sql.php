<?php

namespace Win\Repositories\Database;

/**
 * Cria comandos SQL
 */
class Sql
{
	/** @var string[] */
	public $columns = ['*'];

	/** @var string */
	private $table;

	/** @var array */
	private $values = [];

	/** @var array */
	private $join = [];

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
	 * @param string[] $columns
	 */
	public function __construct($table, $values = [])
	{
		$this->table = $table;
		$this->values = $values;
	}

	/** @param mixed[] */
	public function setValues($values)
	{
		return $this->values = $values;
	}

	/** @return mixed[] */
	public function values()
	{
		return $this->values + $this->whereValues;
	}

	/**
	 * SELECT * FROM ...
	 * @return string
	 */
	public function select()
	{
		return 'SELECT ' . implode(', ', $this->columns)
			. ' FROM ' . $this->table
			. $this->join()
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
		return 'SELECT COUNT(*) FROM ' . $this->table
			. $this->join()
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
		$keys = array_keys($this->values);
		$columns = array_map(fn ($column) => $column . ' = ?', $keys);

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
	 * WHERE ...
	 * @param string $comparator
	 * @param mixed $values
	 */
	public function addWhere($comparator, $values)
	{
		$hasBindParams = preg_match('/[:\?]/', $comparator);
		if ($values && !$hasBindParams) {
			$comparator .= ' = ?';
		}
		$this->where[] = '(' . $comparator . ')';
		$this->whereValues = array_merge($this->whereValues, $values);
	}

	/**
	 * JOIN, LEFT JOIN ...
	 * @param string $join
	 */
	public function addJoin($join)
	{
		$this->join[] = $join;
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

	private function join()
	{
		if (empty($this->join)) {
			return '';
		}

		return ' ' . implode(' ', $this->join);
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
		if (empty($this->limit)) {
			return '';
		}

		return ' LIMIT ' . $this->limit;
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
