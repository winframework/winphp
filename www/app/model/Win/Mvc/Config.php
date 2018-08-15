<?php

namespace Win\Mvc;

/**
 * Configurações
 */
class Config {

	/** @var mixed[] */
	private static $configs;

	final public static function init($config) {
		static::$configs = $config;
	}

	/**
	 * Retorna uma configuração
	 * @param string $key Nome da configuração
	 * @param string $default Valor default, caso esta configuração esteja em branco
	 */
	final public static function get($key, $default = '') {
		return (key_exists($key, static::$configs)) ? static::$configs[$key] : $default;
	}

}
