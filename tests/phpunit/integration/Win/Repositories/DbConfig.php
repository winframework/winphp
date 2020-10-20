<?php

namespace Win\Repositories;

/**
 * Retorna configurações do banco para conexão para Testes
 */
class DbConfig
{
	const DATABASE = [
		'host' => 'localhost',
		'user' => 'root',
		'pass' => 'wcorp@2014Mysql',
		'dbname' => 'winphp_demo'
	];

	/** @return PDO */
	public static function valid()
	{
		return Mysql::connect(static::DATABASE);
	}

	/** @return PDO */
	public static function wrongUser()
	{
		$db = array_merge(static::DATABASE, ['user' => 'wrong']);
		return Mysql::connect($db);
	}

	/** @return PDO */
	public static function wrongPass()
	{
		$db = array_merge(static::DATABASE, ['pass' => 'wrong']);
		return Mysql::connect($db);
	}

	/** @return PDO */
	public static function wrongDb()
	{
		$db = array_merge(static::DATABASE, ['dbname' => 'wrong']);
		return Mysql::connect($db);
	}
}
