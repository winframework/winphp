<?php

namespace Win\Data;

/**
 * Interface para Dados
 */
interface DataInterface {

	/** @return mixed[] */
	public static function getAll();

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public static function set($key, $value);
}
