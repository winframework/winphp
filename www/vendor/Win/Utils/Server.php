<?php

namespace Win\Utils;

/**
 * Utilitário de informações do servidor
 */
abstract class Server
{
	/** @return bool */
	public static function isLocalHost()
	{
		$localAddress = ['localhost', '127.0.0.1', '::1', '', null];

		return in_array(static::getName(), $localAddress) || false !== strpos(static::getName(), '192.168');
	}

	/** @return string */
	public static function getName()
	{
		return Input::server('SERVER_NAME', FILTER_SANITIZE_STRING);
	}
}
