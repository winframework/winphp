<?php


namespace Win\Data;

/**
 * Interface de Dados
 */
interface DataInterface {

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public static function set($key, $value);

	/** @return mixed[] */
	public static function getAll();
}
