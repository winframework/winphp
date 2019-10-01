<?php

namespace Win\Repositories\Database\Orm;

use Win\Repositories\Database\Sql\Query;

/**
 * Permite definir a query manualmente
 */
trait RawTrait
{
	/** @var Query */
	protected $query;

	/**
	 * Define a query manualmente
	 * @param string $raw
	 * @param mixed[] $values
	 */
	public function rawQuery($raw, $values = [])
	{
		$this->query->raw = $raw;
		$this->query->values = $values;

		return $this;
	}

	/**
	 * Executa a query que foi definida em rawQuery
	 * @return bool
	 */
	public function runRaw()
	{
		$this->query->setBuilder('RAW');

		return $this->conn->query($this->query, $this->query->getValues());
	}
}
