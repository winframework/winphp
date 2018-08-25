<?php

namespace Win\Request;

use Win\Data\Data;

/**
 * Header HTTP
 */
class Header extends Data {

	protected static $headers = [];

	public static function getAll() {
		return static::$headers;
	}

	public static function set($key, $value) {
		static::$headers[$key] = $value;
	}

	/**
	 * Adiciona no 'HTTP Header' os valores que foram adicionados
	 * @codeCoverageIgnore
	 */
	public static function run() {
		foreach (static::$headers as $key => $value) {
			header($key . ':' . $value);
		}
		if (key_exists('location', static::$headers)) {
			die();
		}
	}

}
