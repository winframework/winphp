<?php

namespace Win\Connection;

/**
 * Conexão com banco de dados MySQL
 *
 */
use PDO;
use Win\Mvc\Application;
use Win\Mvc\View;

class MySQL implements DataBase {

	/** @var \static */
	static private $instance;

	/** @var \PDO */
	protected $pdo;

	public function getPDO() {
		return $this->pdo;
	}

	/**
	 * @return static
	 */
	static function instance() {
		return static::$instance;
	}

	/**
	 * Cria uma conexão com um banco de dados
	 * @param string[] $dbConfig
	 */
	public function __construct($dbConfig) {
		static::$instance = $this;

		$errorLevel = error_reporting(0);
		$this->pdo = new PDO('mysql:host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['dbname'], $dbConfig['user'], $dbConfig['pass']);
		error_reporting($errorLevel);

		$this->validateConnection();
	}

	private function validateConnection() {
		if ($this->pdo->errorCode()) {
			$data['error'] = $this->pdo->errorInfo();
			Application::app()->view = new View('500', $data);
			Application::app()->setTitle('Problemas de Conexão');
		}
	}

}
