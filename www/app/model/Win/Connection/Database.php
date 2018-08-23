<?php

namespace Win\Connection;

use PDO;
use PDOException;
use Win\DesignPattern\Singleton;
use Win\Mvc\Application;

/**
 * Conexão com banco de dados
 *
 */
abstract class Database {

	use Singleton;

	/** @var PDO */
	protected $pdo;

	/** @var PDOException|null */
	protected $pdoException;

	/**
	 * Cria e retorna conexão PDO
	 * @param string[] $dbConfig
	 * @return PDO
	 */
	abstract protected function createPdo(&$dbConfig);

	/** @return PDO */
	final public function getPDO() {
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
		if (!$this->isValid()):
			Application::app()->errorPage(503);
			Application::app()->view->addData('error', $this->pdoException->getMessage());
		endif;
	}

}
