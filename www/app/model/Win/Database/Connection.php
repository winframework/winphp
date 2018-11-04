<?php

namespace Win\Database;

use PDO;
use PDOException;
use Win\DesignPattern\SingletonTrait;
use Win\Mvc\Application;

/**
 * Conexão com banco de dados
 *
 */
abstract class Connection {

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
	final public function getPdo() {
		return $this->pdo;
	}

	/**
	 * Cria uma conexão com um banco de dados
	 * @param string[] $dbConfig
	 */
	public function connect($dbConfig) {
		try {
			$this->pdo = $this->createPdo($dbConfig);
			$this->pdo->exec("set names utf8");
			$this->pdoException = null;
		} catch (PDOException $ex) {
			$this->pdoException = $ex;
		}
	}

	/**
	 * Retorna TRUE caso a conexão tenha sido bem sucedida
	 * @return boolean
	 */
	public function isValid() {
		return (is_null($this->pdoException) && $this->pdo instanceof \PDO);
	}

	/**
	 * Redireciona para 503 caso a conexão tenha falhado
	 */
	public function validate() {
		if (!is_null($this->pdoException)) {
			Application::app()->errorPage(503, $this->pdoException->getMessage());
		}
	}

}
