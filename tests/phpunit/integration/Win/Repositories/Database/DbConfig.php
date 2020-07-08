<?php

namespace Win\Repositories\Database;

use Win\Application;

use const BASE_PATH;

/**
 * Retorna configurações do banco para conexão para Testes
 */
class DbConfig
{
	const FILE = BASE_PATH . '/config/database.php';

	/** @return string[] */
	public static function valid()
	{
		$db = [];
		require static::FILE;

		return $db;
	}

	/** @return string[] */
	public static function wrongUser()
	{
		$db = static::valid();
		$db['user'] = 'invalid';

		return $db;
	}

	/** @return string[] */
	public static function wrongPass()
	{
		$db = static::valid();
		$db['pass'] = 'this-pass-is-wrong';

		return $db;
	}

	/** @return string[] */
	public static function wrongDb()
	{
		$db = static::valid();
		$db['dbname'] = 'invalid-database';

		return $db;
	}
}
