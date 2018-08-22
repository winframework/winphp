<?php

namespace Win\Data;

/**
 * Dados
 */
abstract class Data implements DataInterface{

	/**
	 * Retorna valor da sessão
	 * @param string $key Nome da configuração
	 * @param string $default Valor default, caso esta configuração esteja em branco
	 */
	public static function get($key, $default = '') {
		$keys = explode('.', $key);
		$config = static::getAll();
		foreach ($keys as $key) {
			if (is_array($config) && array_key_exists($key, $config)) {
				$config = $config[$key];
			} else {
				return $default;
			}
		}
		return $config;
	}

}
