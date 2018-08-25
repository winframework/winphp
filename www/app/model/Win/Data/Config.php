<?php

namespace Win\Data;

/**
 * Configurações
 */
class Config extends Data {

	/** @var mixed[] */
	protected static $config;

	final public static function load($config) {
		static::$config = $config;
	}

	public static function getAll() {
		return static::$config;
	}

	public static function set($key, $value) {
		static::$config[$key] = $value;
	}

}
