<?php

namespace Win\Common\Utils;

/**
 * Manipulador de Senhas
 */
abstract class Password
{
	/**
	 * Chave única que aumenta a segurança das senhas
	 * @var string
	 */
	private static $salt = 'E50H%gDui#';

	/**
	 * Retorna uma senha aleatória
	 * A senha gerada terá sempre pelo menos: 1 símbolo e 2 números
	 * @param int $length
	 * @return string
	 */
	public static function generate($length = 6)
	{
		$letterStr = str_shuffle('abcdefghijkmnopqrstwxyzABCDEFGHJKLMNPQRSTWXY');
		$numberStr = str_shuffle('23456789');
		$specialStr = str_shuffle('@#&');

		$password = substr($letterStr, 0, $length - 3)
				. substr($numberStr, 0, 2)
				. substr($specialStr, 0, 1);

		return str_shuffle($password);
	}

	/**
	 * Adiciona maior segurança a senha
	 * @param string $password
	 */
	public static function encrypt($password)
	{
		return md5($password . self::$salt);
	}
}
