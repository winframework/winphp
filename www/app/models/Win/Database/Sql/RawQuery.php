<?php

namespace Win\Database\Sql;

use Win\Database\Orm;
use Win\Database\Sql\Clauses\Limit;

/**
 * SELECT, UPDATE, DELETE, etc
 */
class RawQuery extends Query
{
	/**
	 * Prepara a query
	 * @param Orm $orm
	 * @param string $rawQuery
	 * $param mixed[] $values
	 */
	public function __construct(Orm $orm, $rawQuery, $values)
	{
		$this->orm = $orm;
		$this->conn = $orm->conn;
		$this->rawQuery = $rawQuery;
		$this->values = $values;
		$this->limit = new Limit();
	}

	public function setStatement($statementType)
	{
		return null;
	}

	/**
	 * Retorna o comando SQL
	 * @return string
	 */
	public function __toString()
	{
		return $this->rawQuery;
	}

	/** @return mixed[] */
	public function getValues()
	{
		return array_values($this->values);
	}
}
