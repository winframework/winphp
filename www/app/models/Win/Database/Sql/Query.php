<?php

namespace Win\Database\Sql;

use Win\Database\Connection;
use Win\Database\Orm;
use Win\Database\Sql\Clauses\Limit;
use Win\Database\Sql\Clauses\OrderBy;
use Win\Database\Sql\Clauses\Where;
use Win\Database\Sql\Statements\Delete;
use Win\Database\Sql\Statements\Select;
use Win\Database\Sql\Statements\Update;

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
	public $table;

	/** @var array */
	public $values;

	/** @var Statement */
	public $statement;

	/** @var Where */
	public $where;

	/** @var OrderBy */
	public $orderBy;

	/** @var Limit */
	public $limit;

	/**
	 * Prepara a query
	 * @param Orm $orm
	 */
	public function __construct(Orm $orm)
	{
		$this->orm = $orm;
		$this->table = $orm::TABLE;
		$this->conn = $orm->conn;
		$this->values = [];

		$this->where = new Where();
		$this->orderBy = new OrderBy();
		$this->limit = new Limit();
	}

	/**
	 * Define a base da Query
	 * @param string $statementType
	 * @example setStatement('SELECT'|'UPDATE'|'DELETE')
	 */
	public function setStatement($statementType)
	{
		$this->statement = Statement::factory($statementType, $this);
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
			var_dump((string) $this->statement, $this->getValues());
		}

		return (string) $this->statement;
	}
}
