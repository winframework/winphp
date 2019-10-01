<?php

namespace Win\Repositories\Database\Sql;

/**
 * WHERE ___ = ?
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
	 * @param string $comparator
	 * @param mixed $values
	 */
	public function add($comparator, ...$values)
	{
		$this->values = array_merge($this->values, $values);
		if (count($values) && strpos($comparator, '?') === false) {
			$comparator .= ' = ?';
		}
		$this->comparators[] = $comparator;
	}

	// public function and(...$comparators)
	// {
	// 	foreach ($comparators as $comparator) {
	// 		$this->add($comparator[0], $comparator[1], $comparator[2]);
	// 	}
	// }
}
