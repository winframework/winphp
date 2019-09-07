<?php

namespace Win\Database\Sql;

use Win\Database\Connection;
use Win\Database\Orm;
use Win\Database\Sql\Clauses\OrderBy;
use Win\Database\Sql\Clauses\Where;
use Win\Database\Sql\Statements\Delete;
use Win\Database\Sql\Statements\Insert;
use Win\Database\Sql\Statements\Select;
use Win\Database\Sql\Statements\SelectCount;
use Win\Database\Sql\Statements\Update;

/**
 * SELECT, UPDATE, DELETE, etc
 */
class Query
{
	/** @var Connection */
	protected $conn;

	/** @var Orm */
	public $orm;

	/** @var string */
	public $table;

	/** @var array */
	public $values;

	/** @var Statement */
	protected $statement;

	/** @var Where */
	public $where;

	/** @var OrderBy */
	public $orderBy;

	/**
	 * Prepara a query
	 * @param Orm $orm
	 */
	public function __construct(Orm $orm)
	{
		$this->orm = $orm;
		$this->table = $orm->table;
		$this->conn = $orm->conn;
		$this->values = [];

		$this->where = new Where($this);
		$this->orderBy = new OrderBy();
	}

	/**
	 * Define a base da Query
	 * @param string $statement
	 * @example setStatement('SELECT'|'UPDATE'|'DELETE')
	 */
	public function setStatement($statement)
	{
		switch ($statement) {
			case 'SELECT':
				$this->statement = new Select($this);
			break;
			case 'SELECT COUNT':
				$this->statement = new SelectCount($this);
			break;
			case 'UPDATE':
				$this->statement = new Update($this);
			break;
			case 'INSERT':
				$this->statement = new Insert($this);
			break;
			case 'DELETE':
				$this->statement = new Delete($this);
			break;
		}
	}

	/**
	 * Retorna o comando SQL
	 * @return string
	 */
	protected function toString()
	{
		return $this->statement->__toString();
	}

	/** @return mixed[] */
	public function getValues()
	{
		return array_values($this->statement->getValues());
	}

	/**
	 * Retorna o comando SQL
	 * @return string
	 */
	public function __toString()
	{
		if ($this->orm->debug) {
			$this->debug();
		}

		return $this->toString();
	}

	/**
	 * Exibe informações de debug
	 */
	public function debug()
	{
		var_dump($this->toString(), $this->getValues());
	}
}
