<?php

namespace Win\Database;

use Win\Database\Connection\Type\Mysql;
use const BASE_PATH;

/**
 * Retorna configurações do banco para conexão
 */
class DbConfig {

	protected static $dbFile = BASE_PATH . '/app/config/database.php';

	/** @return string[] */
	public static function valid() {
		$db = [];
		require static::$dbFile;
		return $db;
	}

	/** @return string[] */
	public static function wrongUser() {
		$db = static::valid();
		$db['user'] = 'invalid';
		return $db;
	}

	/** @return string[] */
	public static function wrongPass() {
		$db = static::valid();
		$db['pass'] = 'this-pass-is-wrong';
		return $db;
	}

	/** @return string[] */
	public static function wrongDb() {
		$db = static::valid();
		$db['dbname'] = 'invalid-database';
		return $db;
	}

	/** @return Mysql */
	public static function connect() {
		$connection = Mysql::instance();
		$connection->connect(static::valid());
		return $connection;
	}

}
