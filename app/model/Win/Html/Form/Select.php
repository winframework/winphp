<?php

/**
 * Select
 * Auxilia nas <select>
 * @author WebCorpore
 */

namespace Win\Html\Form;

class Select {

	private $options = [];
	private $currentValue;

	/**
	 * Cria o select com os <option>'s
	 * @param string[] $options array com titulo dos options
	 * @param string $currentValue option que será selecionado
	 */
	public function __construct($options = [], $currentValue = '') {
		$this->options = $options;
		$this->currentValue = $currentValue;
	}

	/**
	 * Exibe os <option>'s do select
	 * @return string
	 */
	public function __toString() {
		$html = '';
		foreach ($this->options as $option):
			$html .= '<option value="' . $option . '" ' . static::active($option, $this->currentValue) . '>' . $option . '</option>';
		endforeach;
		return $html;
	}

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

}
