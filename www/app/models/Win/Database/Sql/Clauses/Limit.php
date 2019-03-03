<?php

namespace Win\Database\Sql\Clauses;

/**
 * Limit 10
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
	 * @param string $limit
	 */
	public function set($limit)
	{
		$this->limit = $limit;
	}
}
