<?php

namespace Win\Repositories;

use Win\Repositories\Session;

/**
 * Alerta
 * Armazena mensagens de alerta na sessão
 */
class Alert extends Session
{
	public static function instance($group = '')
	{
		return parent::instance('alerts.' . $group);
	}

	/** @param string $message */
	public static function message($message)
	{
		static::instance()->add('default', $message);
	}

	/** @param string $message */
	public static function success($message)
	{
		static::instance()->add('success', $message);
	}

	/** @param string $message */
	public static function error($message)
	{
		static::instance()->add('danger', $message);
	}

	/** @param string $message */
	public static function info($message)
	{
		static::instance()->add('info', $message);
	}

	/** @param string $message */
	public static function warning($message)
	{
		static::instance()->add('warning', $message);
	}
}
