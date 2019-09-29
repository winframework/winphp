<?php

namespace Win\Core\Repositories\Database\Sql;

/**
 * ORDER BY ___ DESC
 */
class OrderBy
{
	/**
	 * Regras de ordenação
	 * @var string[]
	 */
	private $rules;

	/**
	 * Prepara a cláusula SQL
	 */
	public function __construct()
	{
		$this->rules = [];
	}

	/**
	 * Retorna o SQL da cláusula
	 * @return string
	 */
	public function __toString()
	{
		if (!empty($this->rules)) {
			ksort($this->rules);

			return ' ORDER BY ' . implode(', ', $this->rules);
		}

		return '';
	}

	/**
	 * Define a ordenação principal
	 * @param string $orderBy
	 */
	public function set($orderBy)
	{
		$this->rules = [$orderBy];
	}

	/**
	 * Adiciona uma ordenação
	 * @param string $orderBy
	 * @param int $priority
	 */
	public function add($orderBy, $priority = 0)
	{
		$this->rules[$priority] = $orderBy;
	}

	/**
	 * Remove as ordenações
	 */
	public function reset()
	{
		$this->rules = [];
	}
}
