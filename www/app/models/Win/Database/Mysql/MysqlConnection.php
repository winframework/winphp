<?php

namespace Win\Database\Mysql;

use PDO;
use Win\Database\Connection;

/**
 * Conexão com banco de dados MySQL
 */
class MysqlConnection extends Connection
{
	/**
	 * Cria conexão via PDO
	 * @param array $db
	 */
	protected function createPdo(&$db)
	{
		return new PDO(
			'mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
			$db['user'],
			$db['pass'],
			[]
		);
	}
}
