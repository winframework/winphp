<?php

namespace Win\Database\Sql\Clauses;

/**
 * ORDER BY id DESC
 */
class OrderBy
{
	/**
	 * Modo de ordenação
	 * @var string
	 */
	private $orderBy;

	/**
	 * Prepara a cláusula SQL
	 */
	public function __construct()
	{
		$this->orderBy = 'id ASC';
	}

	/**
	 * Retorna o SQL da cláusula
	 * @return string
	 */
	public function __toString()
	{
		if ($this->orderBy) {
			return ' ORDER BY ' . $this->orderBy;
		}

		return '';
	}

	/**
	 * Define a ordenação
	 * @param string $orderBy
	 */
	public function set($orderBy)
	{
		$this->orderBy = $orderBy;
	}
}
