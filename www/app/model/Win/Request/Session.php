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

}
