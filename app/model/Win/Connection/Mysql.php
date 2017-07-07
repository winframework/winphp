<?php

namespace Win\Connection;

use PDO;

/**
 * Conexão com banco de dados MySQL
 * 
 */
class Mysql extends Database {

	/** Cria conexao via PDO */
	protected function connect(&$dbConfig) {
		return new PDO('mysql:host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['dbname'], $dbConfig['user'], $dbConfig['pass']);
	}

}
