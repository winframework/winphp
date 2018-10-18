<?php

namespace Win\Database;

use PDO;

/**
 * Conexão com banco de dados MySQL
 * 
 */
class Mysql extends Connection {

	/** Cria conexão via PDO */
	protected function createPdo(&$dbConfig) {
		return new PDO('mysql:host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['dbname'], $dbConfig['user'], $dbConfig['pass']);
	}

}
