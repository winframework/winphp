<?php

namespace Win\Repositories;

use PDO;

/**
 * Conexão com banco de dados MySQL
 */
class Mysql
{
	/**
	 * Cria conexão via PDO
	 *
	 * @param array $db [
	 * 		'host'=> (string),
	 * 		'dbname' => (string),
	 * 		'user' => (string),
	 * 		'pass' => (string)
	 * 	];
	 * 
	 * @return PDO
	 */
	public static function connect($db)
	{
		try {
			$pdo = new PDO(
				'mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
				$db['user'],
				$db['pass'],
				[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
			);
			$pdo->exec('set names utf8');
			return $pdo;
		} catch (\PDOException $e) {
			throw new DbException('Ocorreu um erro ao conectar o banco de dados.', 503, $e);
		}
	}
}
