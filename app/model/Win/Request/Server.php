<?php

namespace Win\Request;

/**
 * Retorna informações do servidor
 */
class Server {

	public static function isLocalHost() {
		$localAddress = ['localhost', '127.0.0.1', '::1', null];
		return (in_array(static::getName(), $localAddress) || strpos(static::getName(), '192.168') !== false);
	}

	/** @return string */
	public static function getName() {
		return Input::server('SERVER_NAME', FILTER_SANITIZE_STRING);
	}

}
