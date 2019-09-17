<?php

namespace Win\Notifications;

use Win\Mvc\Block;
use Win\Request\Session;

/**
 * Alerta
 * Armazena e exibe mensagens de alerta
 */
class Alert extends Session
{
	/** @var string */
	const BLOCK = 'notifications/alerts';

	/**
	 * Retorna os alertas da sessão, filtrando por grupo
	 * @return static
	 * @param string $group
	 */
	public static function alerts($group = 'default')
	{
		return static::instance('alerts.' . $group);
	}

	/**
	 * Retorna o html dos alertas
	 * @return Block
	 */
	public function __toString()
	{
		$alerts = ['alerts' => $this->popAll() ?: []];

		return (new Block(static::BLOCK, $alerts))->__toString();
	}

	/** @param string $message */
	public static function default($message)
	{
		static::alerts()->add('default', $message);
	}

	/** @param string $message */
	public static function success($message)
	{
		static::alerts()->add('success', $message);
	}

	/** @param string $message */
	public static function error($message)
	{
		static::alerts()->add('danger', $message);
	}

	/** @param string $message */
	public static function info($message)
	{
		static::alerts()->add('info', $message);
	}

	/** @param string $message */
	public static function warning($message)
	{
		static::alerts()->add('warning', $message);
	}

	/**
	 * Cria um alerta de erro ou sucesso em um único método
	 * @param string|null $error
	 * @param string $success
	 */
	public static function create($error, $success)
	{
		if (!is_null($error)) {
			static::error($error);
		} else {
			static::success($success);
		}
	}
}
