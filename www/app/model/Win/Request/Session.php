<?php

namespace Win\Request;

use Win\Data\Data;

/**
 * Variáveis de $_SESSION
 */
class Session extends Data {

	public static function instance($alias = 'default') {
		$instance = parent::instance($alias);
		$instance->data = &$_SESSION[$alias];
		return $instance;
	}

	public function all($clear = false) {
		$values = parent::all();
		if ($clear) {
			$this->clear();
		}
		return $values;
	}

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
