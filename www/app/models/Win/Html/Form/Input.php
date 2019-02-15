<?php

namespace Win\Html\Form;

use Win\Html\Html;

/**
 * Input Abstrato
 * <input>
 */
abstract class Input extends Html {

	/** @var string */
	protected $type;

	/** @var string */
	protected $value;

	/**
	 * @param string $type
	 * @param string $name
	 * @param mixed $value
	 * @param string[] $attributes
	 */
	public function __construct($type, $name, $value, $attributes) {
		$this->type = $type;
		$this->value = $value;
		parent::__construct($name, $attributes);
	}

	/** @return string */
	public function getValue() {
		return $this->value;
	}


}
