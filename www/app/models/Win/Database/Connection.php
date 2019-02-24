<?php

namespace Win\Database;

use PDO;
use PDOException;
use PDOStatement;
use Win\Mvc\Application;
use Win\Singleton\SingletonTrait;

/**
 * Conexão com banco de dados
 */
abstract class Connection
{
	use SingletonTrait;

	/** @var PDO */
	protected $pdo;

	/** @var PDOException|null */
	protected $pdoException;

	/**
	 * Cria e retorna conexão PDO
	 * @param string[] $dbConfig
	 * @throws PDOException
	 * @return PDO
	 */
	abstract protected function createPdo(&$dbConfig);

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
			Orm::setConnection($this);
		} catch (PDOException $ex) {
			$this->pdoException = $ex;
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
	 * Redireciona para 503 caso a conexão tenha falhado
	 */
	public function validate()
	{
		if (!is_null($this->pdoException)) {
			Application::app()->errorPage(503, $this->pdoException->getMessage());
		}
	}

	/**
	 * @param string $query
	 * @param mixed[] $values
	 * @return bool
	 */
	public function query($query, $values = [])
	{
		$stmt = $this->pdo->prepare($query);

		return $stmt->execute($values);
	}

	/**
	 * @param string $query
	 * @param mixed[] $values
	 * @return PDOStatement|bool
	 */
	public function stmt($query, $values = [])
	{
		$stmt = $this->pdo->prepare($query);
		$stmt->execute($values);

		return $stmt;
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