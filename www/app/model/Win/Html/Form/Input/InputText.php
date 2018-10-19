<?php

namespace Win\Html\Form\Input;

use Win\Html\Form\Input;

/**
 * Input de Texto
 * <input type="text">
 */
class InputText extends Input {

	/**
	 * @param string $name
	 * @param string $value
	 * @param string[] $attributes
	 */
	public function __construct($name, $value = '', $attributes = []) {
		parent::__construct('text', $name, $value, $attributes);
	}

	/** @return string */
	public function html() {
		return '<input type="' . $this->type . '" name="' . $this->name . '" value="' . $this->value . '" ' . $this->attributes() . '/>';
	}

}
