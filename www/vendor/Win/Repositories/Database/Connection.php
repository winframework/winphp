<?php

namespace Win\Repositories\Database;

use PDO;
use PDOException;
use PDOStatement;
use Win\HttpException;

/**
 * Conexão com banco de dados
 */
abstract class Connection
{
	protected ?PDO $pdo;

	/**
	 * Cria e retorna conexão PDO
	 * @param string[] $db Configuração de Conexão
	 * @throws PDOException
	 * @return PDO
	 */
	abstract protected function createPdo(&$db);

	/** @return PDO */
	public function getPdo()
	{
		return $this->pdo;
	}

	/**
	 * Cria uma conexão com um banco de dados
	 * @param string[] $dbConfig
	 */
	public function __construct($dbConfig)
	{
		try {
			$this->pdo = $this->createPdo($dbConfig);
			$this->pdo->exec('set names utf8');
		} catch (\PDOException $e) {
			$message = 'Houve um erro ao conectar o banco de dados [' . $e->getCode() . '].';
			throw new HttpException($message, 503, $e);
		}
	}

	/**
	 * Retorna TRUE caso a conexão tenha sido bem sucedida
	 * @return bool
	 */
	public function isValid()
	{
		return $this->pdo instanceof \PDO;
	}

	/**
	 * @param string $query
	 * @param mixed[] $values
	 * @return bool
	 */
	public function execute($query, $values = [])
	{
		try {
			$stmt = $this->pdo->prepare($query);
			return $stmt->execute($values);
		} catch (PDOException $e) {
			throw new DatabaseException($e);
		}
	}

	/**
	 * @param string $query
	 * @param mixed[] $values
	 * @return PDOStatement|bool
	 */
	public function stmt($query, $values = [])
	{
		try {
			$stmt = $this->pdo->prepare($query);
			$stmt->execute($values);

			return $stmt;
		} catch (PDOException $e) {
			throw new DatabaseException($e);
		}
	}

	/**
	 * @param string $query
	 * @param mixed[] $values
	 * @return mixed[]
	 */
	public function fetchAll($query, $values = [])
	{
		return $this->stmt($query, $values)->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * @param string $query
	 * @param mixed[] $values
	 * @return mixed[]
	 */
	public function fetch($query, $values)
	{
		return $this->stmt($query, $values)->fetch();
	}

	/**
	 * @param string $query
	 * @param mixed[] $values
	 * @return int
	 */
	public function fetchCount($query, $values)
	{
		return (int) $this->stmt($query, $values)->fetchColumn();
	}

	/** @return int */
	public function lastInsertId()
	{
		return (int) $this->pdo->lastInsertId();
	}
}
