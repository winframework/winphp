<?php

namespace Win\Data;

/**
 * Dados
 */
abstract class Data {

	/**
	 * Retorna valor da sessão
	 * @param string $key Nome da configuração
	 * @param string $default Valor default, caso esta configuração esteja em branco
	 */
	public static function get($key, $default = '') {
		$keys = explode('.', $key);
		$config = static::getAll();
		foreach ($keys as $k) {
			if (is_array($config) && array_key_exists($k, $config)) {
				$config = $config[$k];
			} else {
				return $default;
			}
		}
		return $config;
	}

	/** @return mixed[] */
	abstract protected static function getAll();

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	abstract public static function set($key, $value);
}
