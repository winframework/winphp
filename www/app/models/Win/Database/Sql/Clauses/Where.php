<?php

namespace Win\Database\Sql\Clauses;

/**
 * WHERE id = ?
 */
class Where
{
	/**
	 * Comparadores (column + operator)
	 * @var string[]
	 * @example ['Id > ?', 'Name LIKE ?', 'Age IS NULL']
	 */
	private $comparators;

	/**
	 * Valor a ser comparado
	 * @var mixed[]
	 * @example [10, 'John']
	 */
	public $values;

	/**
	 * Prepara a cláusula Where
	 */
	public function __construct()
	{
		$this->comparators = [];
		$this->values = [];
	}

	/**
	 * Retorna o SQL
	 * @return string
	 */
	public function __toString()
	{
		if (count($this->comparators)) {
			return ' WHERE ' . implode(' AND ', $this->comparators);
		}

		return '';
	}

	/**
	 * Adiciona uma comparação
	 * @param string $column
	 * @param string $operator
	 * @param mixed $value
	 */
	public function add($column, $operator, $value = null)
	{
		if (is_null($value)) {
			$this->comparators[] = $column . ' ' . $operator;
		} else {
			$this->values[] = $value;
			$this->comparators[] = $column . ' ' . $operator . ' ?';
		}
	}

	// public function and(...$comparators)
	// {
	// 	foreach ($comparators as $rule) {
	// 		$this->add($rule[0], $rule[1], $rule[2]);
	// 	}
	// }
}
