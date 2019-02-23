<?php

namespace Win\Authentication;

/**
 * Manipulador de Senhas
 */
abstract class Password
{
	private static $salt = 'E50H%gDui#';

	/**
	 * Retorna uma senha aleatória
	 * A senha gerada terá sempre pelo menos: 1 símbolo e 2 números
	 *
	 * @param int $length
	 *
	 * @return string
	 */
	public static function generate($length = 6)
	{
		$letters = str_shuffle('abcdefghijkmnopqrstwxyzABCDEFGHJKLMNPQRSTWXY');
		$numbers = str_shuffle('23456789');
		$specials = str_shuffle('@#&');

		$password = substr($letters, 0, $length - 3)
				. substr($numbers, 0, 2)
				. substr($specials, 0, 1);

		return str_shuffle($password);
	}

	/**
	 * Adiciona maior segurança a senha
	 *
	 * @param string $password
	 */
	public static function encrypt($password)
	{
		return md5($password . self::$salt);
	}
}
