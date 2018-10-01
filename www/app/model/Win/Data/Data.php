<?php

namespace Win\Data;

use Win\DesignPattern\SingletonTrait;

/**
 * Dados
 */
class Data implements DataInterface {

	use SingletonTrait;

	protected $data = [];

	/**
	 * Retorna valor
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

	public function all() {
		return $this->data;
	}

	public function set($key, $value) {
		$p = &$this->data;
		$keys = explode('.', $key);
		foreach ($keys as $key) {
			$p = &$p[$key];
		}
		if ($value) {
			$p = $value;
		}
	}

	public function clear() {
		$this->data = [];
	}

	public function delete($key) {
		unset($this->data[$key]);
	}

}
