<?php

namespace Win\Data;

/**
 * Interface para Dados
 */
interface DataInterface {

	/**
	 * Retorna todos os dados
	 * @return mixed[]
	 */
	public function all();

	/**
	 * Exclui todos os dados
	 */
	public function clear();

	/**
	 * Define um dado
	 * @param string $key
	 * @param mixed $value
	 */
	public function set($key, $value);

	/**
	 * Retorna um dado
	 * @return mixed
	 * @param string $key
	 * @param mixed $default
	 */
	public function get($key, $default);

	/**
	 * Exclui um dado
	 * @param string $key
	 */
	public function delete($key);
}
