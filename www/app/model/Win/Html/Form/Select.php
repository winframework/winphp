<?php

namespace Win\Html\Form;

use Win\Html\Html;

/**
 * Select
 * <select>
 *
 */
class Select extends Html {

	protected $options;
	protected $value;

	/**
	 * Retorna "selected" se os valores são iguais
	 * @param mixed $value1
	 * @param mixed $value2
	 * @return string
	 */
	public static function select($value1, $value2 = true) {
		return ($value1 == $value2) ? 'selected ' : '';
	}

	/**
	 * Cria um <select>
	 * @param string $name
	 * @param string[] $options
	 * @param string|int $value
	 * @param string[] $attributes
	 */
	public function __construct($name, $options, $value = null, $attributes = []) {
		if (is_string($value) && in_array($value, $options)) {
			$value = array_search($value, $options);
		}
		$this->options = $options;
		$this->value = $value;
		parent::__construct($name, $attributes);
	}

	/** @return string */
	public function getValue() {
		$value = '';
		if (key_exists($this->value, $this->options)) {
			$value = $this->options[$this->value];
		}
		return $value;
	}

	/** @return string */
	public function html() {
		return '<select name="' . $this->name . '" ' . $this->attributes() . '> '
				. $this->htmlContent()
				. '</select>';
	}

	/**
	 * Retorna o HTML dos <options>
	 * @return string
	 */
	public function htmlContent() {
		return $this->htmlOptions($this->options);
	}

	/** @return string */
	protected function htmlOptions($options = []) {
		$html = '';
		foreach ($options as $value => $option) {
			if (is_string($option)) {
				$html .= '<option ' . static::select($value, $this->value) . 'value="' . $value . '">' . $option . '</option> ';
			} else {
				$html .= '<optgroup label="' . $value . '"> ' . $this->htmlOptions($option) . '</optgroup>';
			}
		}
		return $html;
	}

}
