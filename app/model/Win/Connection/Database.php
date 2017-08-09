<?php

namespace Win\Connection;

/**
 * Conexão com banco de dados
 *
 */
use PDO;
use Win\Mvc\Application;

abstract class Database {

	/** @var Database */
	protected static $instance;

	/** @var PDO */
	protected $pdo;

	/**
	 * Cria e retorna conexao PDO
	 * @param string[] $dbConfig
	 * @return PDO
	 */
	abstract protected function connect(&$dbConfig);

	/** @return PDO */
	final public function getPDO() {
		return $this->pdo;
	}

	/** @return static */
	final public static function instance() {
		return self::$instance;
	}

	/**
	 * Cria uma conexão com um banco de dados
	 * @param string[] $dbConfig
	 */
	public function __construct($dbConfig) {
		self::$instance = $this;
		try {
			$this->pdo = $this->connect($dbConfig);
			$this->pdo->exec("set names utf8");
		} catch (\PDOException $ex) {
			Application::app()->errorPage(503);
			Application::app()->view->addData('error', $ex->getMessage());
		}
	}

	/**
	 * Redireciona para 503 caso não haja conexao
	 * @param boolean $connection
	 */
	public static function validate($connection = false) {
		if ($connection === false):
			Application::app()->errorPage(503);
		endif;
	}

}
