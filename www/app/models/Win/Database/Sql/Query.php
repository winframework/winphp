<?php

namespace Win\Database\Sql;

use Win\Database\Connection;
use Win\Database\Orm;

/**
 * SELECT, UPDATE, DELETE, etc
 */
class Query
{
	/** @var Connection */
	protected $conn;

	/** @var Orm */
	protected $orm;

	/** @var string */
	protected $table;

	protected $statement;

	/** @var array */
	protected $values;

	/**
	 * Prepara a query
	 * @param Orm $orm
	 */
	public function __construct(Orm $orm)
	{
		$this->orm = $orm;
		$this->table = $orm->table;
		$this->conn = $orm->getConnection();
		$this->values = [];
	}

	/**
	 * Retorna o comando SQL
	 * @return string
	 */
	protected function toString()
	{
		switch ($this->statement) {
			case 'SELECT':
				return 'SELECT * FROM ' . $this->table;
			break;
			case 'SELECT_COUNT':
				return 'SELECT count(*) FROM ' . $this->table;
			break;
			case 'UPDATE':
				$sets = array_map(function ($column) {
					return $column . ' = ?';
				}, $this->getKeys());

				return 'UPDATE ' . $this->table
				. ' SET ' . implode(', ', $sets)
				. ' WHERE Id = ' . $this->values['Id'];
			break;
			case 'INSERT':
				return 'INSERT INTO ' . $this->table . ' (' . implode(',', $this->getKeys()) . ')'
				. ' VALUES (' . implode(', ', $this->getBindParams()) . ')';
			break;
			case 'DELETE':
				return 'DELETE FROM ' . $this->table;
			break;
		}

		return '';
	}

	/**
	 * @return string[]
	 * @example return ['?','?','?']
	 */
	protected function getBindParams()
	{
		return str_split(str_repeat('?', count($this->values)));
	}

	/** @return mixed[] */
	public function getValues()
	{
		return array_values($this->values);
	}

	/** @return mixed[] */
	public function getKeys()
	{
		return array_keys($this->values);
	}

	/**
	 * Define os valores
	 * @param mixed[] $values
	 */
	public function setValues($values)
	{
		$this->values = $values;
	}

	public function setStatement($statement)
	{
		$this->statement = $statement;
	}

	/**
	 * Retorna o comando SQL
	 * @return string
	 */
	public function __toString()
	{
		if ($this->orm->getDebugMode()) {
			$this->debug();
		}

		return $this->toString();
	}

	/**
	 * Exibe informações de debug
	 */
	public function debug()
	{
		print_r('<pre>');
		print_r((string) $this->toString());
		print_r('</pre>');
	}
}
