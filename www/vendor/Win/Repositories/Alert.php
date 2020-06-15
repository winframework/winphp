<?php

namespace Win\Repositories;

use Exception;
use Win\Repositories\Session;

/**
 * Alerta
 * Armazena mensagens de alerta na sessão
 */
class Alert
{

        public static function get($group = '')
        {
                 return Application::app()->session->pop($group . 'alerts');
        }

	/** @param string $message */
	public static function add($message, type= "default", group = '')
	{
		Application::app->session->add($group . 'alerts.' . type, $message);
	}

	/** @param string $message */
	public static function success($message)
	{
		static::add($message,'success');
	}

	/** @param string|Exception $message */
	public static function error($message)
	{
		if ($message instanceof Exception) {
			$message = $message->getMessage();
		}
		static::add($message, 'danger');
	}

	/** @param string $message */
	public static function info($message)
	{
		static::add($message, 'info');
	}

	/** @param string $message */
	public static function warning($message)
	{
		static::add($message, 'warning');
	}
}
