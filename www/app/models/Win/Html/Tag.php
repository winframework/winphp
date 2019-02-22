<?php

namespace Win\Html;

/**
 * Tag Html
 */
abstract class Tag
{
	/** @var string */
	protected $name;

	/** @var string[] */
	protected $attributes;

	/** @return string */
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

	/** @return string */
	public function __toString()
	{
		return $this->html();
	}

	/** @return string */
	protected function attributes()
	{
		$html = '';
		foreach ($this->attributes as $name => $value) {
			$html .= $name . '="' . $value . '" ';
		}

		return $html;
	}
}
