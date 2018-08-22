<?php

namespace Win\Request;

use Win\Data\Data;

/**
 * Variáveis de $_SESSION
 */
class Session extends Data {

	const TYPE = 'default';

	public static function getAll() {
		return $_SESSION;
	}

	public static function set($key, $value) {
		$_SESSION[$key] = $value;
	}

}
