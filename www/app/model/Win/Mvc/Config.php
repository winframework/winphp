<?php

namespace Win\Mvc;

/**
 * Configurações
 */
class Config {

	/** @var mixed[] */
	private static $config;

	final public static function init($config) {
		static::$config = $config;
	}

	/**
	 * Retorna uma configuração
	 * @param string $key Nome da configuração
	 * @param string $default Valor default, caso esta configuração esteja em branco
	 */
	final public static function get($key, $default = '') {
		return (key_exists($key, self::$config)) ? self::$config[$key] : $default;
	}

}
