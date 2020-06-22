<?php

namespace Win\Repositories\Database;

use PDO;

/**
 * Conexão com banco de dados MySQL
 */
class Mysql
{
	/**
	 * Cria conexão via PDO
	 * @param array $db
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
			throw new DbException('Ocorreu um erro ao conectar o banco de dados.', $e);
		}
	}
}
