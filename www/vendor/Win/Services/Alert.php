<?php

namespace Win\Services;

use Exception;

/**
 * Alerta
 * Armazena mensagens de alerta na sessão
 */
class Alert
{
	/**
	 * Retorna e remove os alertas da sessão
	 * @return string[]
	 */
	public static function popAll()
	{
		$alerts = $_SESSION["alerts"] ?? [];
		$_SESSION["alerts"] = [];
		return $alerts;
	}
	/**
	 * Adiciona um alerta na sessão
	 * @param string $message
	 * @param string $type
	 */
	public static function add($message, $type = "default")
	{
		$_SESSION["alerts"][$type][] = $message;
	}

	/** @param string $message */
	public static function success($message)
	{
		static::add($message, 'success');
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
