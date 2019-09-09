<?php

namespace Win\Database\Sql\Clauses;

/**
 * LIMIT ___
 */
class Limit
{
	/**
	 * Limit inicial e final
	 * @var string
	 */
	private $limit;

	/**
	 * Prepara a cláusula SQL
	 */
	public function __construct()
	{
		$this->limit = '';
	}

	/**
	 * Retorna o SQL da cláusula
	 * @return string
	 */
	public function __toString()
	{
		if ($this->limit) {
			return ' LIMIT ' . $this->limit;
		}

		return '';
	}

	/**
	 * Define o limit
	 * @param int $offset
	 * @param int $limit
	 */
	public function set($offset, $limit)
	{
		$this->limit = $offset . ',' . $limit;
	}

	/**
	 * Remove o limite
	 */
	public function reset()
	{
		$this->limit = '';
	}
}
