<?php

namespace Win\Request;

use Win\Data\Data;

/**
 * Header HTTP
 */
class Header extends Data {

	/**
	 * Adiciona no 'HTTP Header' os valores que foram adicionados
	 * @codeCoverageIgnore
	 */
	public function run() {
		foreach ($this->all() as $key => $value) {
			header($key . ':' . $value);
		}
		if ($this->get('location')) {
			die();
		}
	}

}
