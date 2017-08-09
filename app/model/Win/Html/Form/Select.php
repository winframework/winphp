<?php

/**
 * Select
 * Auxilia nas <select>
 *
 */

namespace Win\Html\Form;

class Select {

	protected $options;
	private $current;

	/**
	 * Retorna selected="true" se os valores são iguais
	 * @param mixed $value1
	 * @param mixed $value2
	 */
	public static function active($value1, $value2 = true) {
		if ($value1 == $value2) {
			return 'selected="true"';
		}
	}

	/**
	 * Cria um select com options, selecionando automatico
	 * @param string[] $options
	 * @param string $current
	 */
	public function __construct($options, $current = '') {
		$this->options = $options;
		$this->current = $current;
	}

	/**
	 * Exibe os options do select
	 * @return string
	 */
	public function __toString() {
		$html = '';
		foreach ($this->options as $option):
			$html .= '<option ' . static::active($option, $this->current) . ' value="' . $option . '">' . $option . '</option>';
		endforeach;
		return $html;
	}

}
