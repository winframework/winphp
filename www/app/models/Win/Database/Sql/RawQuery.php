<?php

namespace Win\Database\Sql;

use Win\Database\Orm;

/**
 * SELECT, UPDATE, DELETE, etc
 */
class RawQuery extends Query
{
	/**
	 * Prepara a query
	 * @param Orm $orm
	 */
	public function __construct(Orm $orm, $rawQuery, $values)
	{
		$this->orm = $orm;
		$this->conn = $orm->conn;
		$this->rawQuery = $rawQuery;
		$this->values = $values;
	}

	public function setStatement($statementType)
	{
		return null;
	}

	/**
	 * Retorna o comando SQL
	 * @return string
	 */
	protected function toString()
	{
		return $this->rawQuery;
	}

	/** @return mixed[] */
	public function getValues()
	{
		return array_values($this->values);
	}
}
