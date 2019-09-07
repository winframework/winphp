<?php

namespace Win\Database\Orm\Traits;

use Exception;
use Win\Database\Sql\RawQuery;

/**
 * Permite definir a query manualmente
 */
trait RawSql
{
	/**
	 * Define a query manualmente
	 * @param string $query
	 * @param mixed[] $values
	 */
	public function rawQuery($query, $values = [])
	{
		$this->query = new RawQuery($this, $query, $values);

		return $this;
	}

	/**
	 * Executa a Query (apenas para RAW)
	 * @return bool
	 */
	public function run()
	{
		if (!$this->query instanceof RawQuery) {
			throw new Exception('ORM run only works with RawQuery');
		}

		return $this->conn->query($this->query, $this->query->values);
	}
}
