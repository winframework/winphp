<?php

namespace Win\Data;

use Win\Singleton\SingletonTrait;

/**
 * Armazena Dados
 */
class Data implements DataInterface {

	use SingletonTrait;

	/** @var mixed[] */
	protected $data = [];

	/**
	 * Define todos os valores
	 * @param mixed[] $values
	 */
	public function load($values) {
		$this->data = $values;
	}

	/**
	 * Retorna todas as variáveis
	 * @return mixed[]
	 */
	public function all() {
		return $this->data;
	}

	/** Exclui todos os dados */
	public function clear() {
		$this->data = [];
	}

	/**
	 * Define um valor
	 * @param string $key
	 * @param mixed $value
	 */
	public function set($key, $value) {
		$p = &$this->data;
		$keys = explode('.', $key);
		foreach ($keys as $k) {
			$p = &$p[$k];
		}
		if ($value) {
			$p = $value;
		}
	}

	/**
	 * Adiciona um valor ao array
	 * @param string $key
	 * @param mixed $value
	 */
	public function add($key, $value) {
		$values = $this->getArray($key);
		array_push($values, $value);
		$this->set($key, $values);
	}

	/**
	 * Retorna um valor
	 * @param string $key
	 * @param string $default Valor default, caso a $key não exista
	 */
	public function get($key, $default = '') {
		$data = $this->data;
		$keys = explode('.', $key);
		foreach ($keys as $k) {
			if (is_array($data) && array_key_exists($k, $data)) {
				$data = $data[$k];
			} else {
				return $default;
			}
		}
		return $data;
	}

	/**
	 * Retorna sempre um array
	 * @param mixed $key
	 * @return mixed[]
	 */
	private function getArray($key) {
		$values = $this->get($key, []);
		if (!is_array($values) && $this->has($key)) {
			$values = [$this->get($key)];
		}
		return $values;
	}

	/** @param string $key */
	public function delete($key) {
		unset($this->data[$key]);
	}

	/** @return boolean */
	public function has($key) {
		return (!is_null($this->get($key, null)));
	}

}
