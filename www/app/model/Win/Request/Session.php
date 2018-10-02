<?php

namespace Win\Request;

use Win\Data\Data;

/**
 * Variáveis de $_SESSION
 */
class Session extends Data {

	/**
	 * Retorna instância de Session
	 * @param string $alias
	 * @return
	 */
	public static function instance($alias = 'default') {
		$instance = parent::instance($alias);
		$instance->data = &$_SESSION[$alias];
		return $instance;
	}

	/**
	 * Retorna todas variáveis da sessão
	 * @param string $clear TRUE irá também remover todas as variáveis
	 * @return mixed[]
	 */
	public function all($clear = false) {
		$values = parent::all();
		if ($clear) {
			$this->clear();
		}
		return $values;
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @param boolean $delete TRUE também irá remover a variável
	 * @return mixed
	 */
	public function get($key, $default = '', $delete = false) {
		$value = parent::get($key, $default);
		if ($delete) {
			$this->delete($key);
		}
		return $value;
	}

	/** @return boolean */
	public function has($key) {
		return (!is_null($this->get($key, null)));
	}

}
