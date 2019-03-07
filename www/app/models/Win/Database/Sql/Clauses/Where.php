<?php

namespace Win\Database\Sql\Clauses;

/**
 * WHERE id = ?
 */
class Where
{
	/**
	 * Nome dos Filtros
	 * @var array
	 */
	private $filters;

	/**
	 * Valor dos filtros
	 * @var array
	 */
	private $values;

	/**
	 * Prepara a cláusula Where
	 */
	public function __construct()
	{
		$this->filters = [];
	}

	/**
	 * Retorna o SQL da cláusula
	 * @return string
	 */
	public function __toString()
	{
		if (count($this->filters)) {
			return ' WHERE ' . implode(' AND ', $this->filters);
		}

		return '';
	}

	/**
	 * Adiciona um filtro where
	 * @param string $column
	 * @param string $operator
	 * @param mixed $value
	 */
	public function add($column, $operator, $value)
	{
		$this->filters[] = $column . $operator . '?';
		$this->values[] = $value;
	}

	/**
	 * Retorna os valores
	 * @return mixed[]
	 */
	public function values()
	{
		return $this->values;
	}
}
