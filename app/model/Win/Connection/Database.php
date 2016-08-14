<?php

namespace Win\Connection;

/**
 * Conexão com banco de dados
 *
 */
use PDO;
use Win\Mvc\Application;
use Win\Mvc\View;

abstract class Database {

	/** @var \static */
	static protected $instance;

	/** @var PDO */
	protected $pdo;

	/**
	 * Cria e retorna conexao PDO
	 * @param string[] $dbConfig
	 * @return PDO
	 */
	abstract function connect(&$dbConfig);

	/** @return PDO */
	final public function getPDO() {
		return $this->pdo;
	}

	/** @return static */
	final static function instance() {
		return self::$instance;
	}

	/**
	 * Cria uma conexão com um banco de dados
	 * @param string[] $dbConfig
	 */
	public function __construct($dbConfig) {
		self::$instance = $this;

		$errorLevel = error_reporting(0);
		$this->pdo = $this->connect($dbConfig);
		error_reporting($errorLevel);

		$this->validateConnection();
	}

	protected function validateConnection() {
		if ($this->pdo->errorCode()) {
			$data['error'] = $this->pdo->errorInfo();
			Application::app()->view = new View('500', $data);
			Application::app()->setTitle('Problemas de Conexão');
		}
	}

}
