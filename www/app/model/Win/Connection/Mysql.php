<?php

namespace Win\Connection;

use PDO;

/**
 * Conexão com banco de dados MySQL
 * 
 */
class Mysql extends Database {

	/** Cria conexão via PDO */
	protected function createPdo(&$dbConfig) {
		return new PDO('mysql:host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['dbname'], $dbConfig['user'], $dbConfig['pass']);
	}

}
