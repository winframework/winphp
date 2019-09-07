<?php

namespace Win\Database\Sql\Clauses;

/**
 * ORDER BY id DESC
 */
class OrderBy
{
	/**
	 * Sequencias de listagem
	 * @var string[]
	 */
	private $ordinations;

	/**
	 * Prepara a cláusula SQL
	 */
	public function __construct()
	{
		$this->ordinations = ['id ASC'];
	}

	/**
	 * Retorna o SQL da cláusula
	 * @return string
	 */
	public function __toString()
	{
		if ($this->ordinations) {
			return ' ORDER BY ' . implode(', ', $this->ordinations);
		}

		return '';
	}

	/**
	 * Define a ordenação principal
	 * @param string $orderBy
	 */
	public function set($orderBy)
	{
		$this->ordinations = [$orderBy];
	}

	/**
	 * Adiciona uma ordenação
	 * @param string $orderBy
	 * @param int $priority
	 */
	public function add($orderBy, $priority = 0)
	{
		$this->ordinations[$priority] = $orderBy;
	}

	/**
	 * Remove as ordenações
	 */
	public function clear()
	{
		$this->ordinations = [];
	}
}
