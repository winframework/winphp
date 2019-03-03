<?php

namespace Win\Html;

/**
 * Tag Html
 */
abstract class Tag
{
	/**
	 * Nome da tag
	 * @var string
	 * @example a|input|form|img|
	 */
	protected $name;

	/**
	 * Atributos HTML
	 * @var string[]
	 * @example id|class|href|
	 */
	protected $attributes;

	/**
	 * Retorna o código html da tag
	 * @return string
	 */
	abstract public function html();

	/**
	 * Cria o elemento
	 * @param string $name
	 * @param string[] $attributes
	 */
	public function __construct($name, $attributes)
	{
		$this->name = $name;
		$this->attributes = $attributes;
	}

	/**
	 * Retorna o código html da tag
	 * @return string
	 */
	public function __toString()
	{
		return $this->html();
	}

	/**
	 * Retorna o html dos atributos
	 * @return string
	 */
	protected function attributes()
	{
		$html = '';
		foreach ($this->attributes as $name => $value) {
			$html .= $name . '="' . $value . '" ';
		}

		return $html;
	}
}
