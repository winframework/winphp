<?php

namespace Win\Data;

/**
 * Configurações
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
