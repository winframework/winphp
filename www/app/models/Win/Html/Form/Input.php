<?php

namespace Win\Html\Form;

use Win\Html\Tag;

/**
 * Input Abstrato
 * <input>
 */
abstract class Input extends Tag
{
	/**
	 * Tipo do input
	 * @var string
	 */
	protected $type;

	/**
	 * Valor atual
	 * @var string
	 */
	protected $value;

	/**
	 * Cria um <input>
	 * @param string $type
	 * @param string $name
	 * @param mixed $value
	 * @param string[] $attributes
	 */
	public function __construct($type, $name, $value, $attributes)
	{
		$this->type = $type;
		$this->value = $value;
		parent::__construct($name, $attributes);
	}

	/**
	 * Retorna o valor atual
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}
}
