<?php

namespace Win\Database\Connection;

use PDO;

/**
 * Conexão com banco de dados MySQL
 * 
 */
class MysqlConnection extends Connection {

	/** Cria conexão via PDO */
	protected function createPdo(&$dbConfig) {
		return new PDO(
			'mysql:host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['dbname'],
			$dbConfig['user'],
			$dbConfig['pass']
		);
	}

}
