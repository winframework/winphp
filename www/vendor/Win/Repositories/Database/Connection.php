<?php

namespace Win\Repositories\Database;

use PDO;
use PDOException;
use PDOStatement;
use Win\Common\Traits\SingletonTrait;
use Win\Response\ResponseException;

/**
 * Conexão com banco de dados
 */
abstract class Connection
{
	use SingletonTrait;

	/** @var PDO */
	protected $pdo;

	/** @var PDOException */
	public $pdoException;

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
	public function connect($dbConfig)
	{
		try {
			$this->pdo = $this->createPdo($dbConfig);
			$this->pdo->exec('set names utf8');
			$this->pdoException = null;
		} catch (\PDOException $e) {
			$message = 'Houve um erro ao conectar o banco de dados';
			throw new ResponseException($message, 503, $e);
		}
	}

	/**
	 * Retorna TRUE caso a conexão tenha sido bem sucedida
	 * @return bool
	 */
	public function isValid()
	{
		return is_null($this->pdoException) && $this->pdo instanceof \PDO;
	}

	/**
	 * @param string $query
	 * @param mixed[] $values
	 * @return bool
	 */
	public function query($query, $values = [])
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
		$stmt = $this->stmt($query, $values);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * @param string $query
	 * @param mixed[] $values
	 * @return mixed[]
	 */
	public function fetch($query, $values)
	{
		$stmt = $this->stmt($query, $values);

		return $stmt->fetch();
	}

	/**
	 * @param string $query
	 * @param mixed[] $values
	 * @return int
	 */
	public function fetchCount($query, $values)
	{
		$stmt = $this->stmt($query, $values);

		return $stmt->fetchColumn();
	}

	/** @return string */
	public function getLastInsertId()
	{
		return $this->pdo->lastInsertId();
	}
}
