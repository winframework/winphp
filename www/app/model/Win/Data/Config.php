<?php

namespace Win\Data;

/**
 * Armazena Configurações
 */
class Config extends Data {

	/**
	 * Define todos os valores
	 * @param mixed[] $values
	 */
	public function load($values) {
		$this->data = $values;
	}

}
