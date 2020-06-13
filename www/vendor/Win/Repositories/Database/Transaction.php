<?php

namespace Win\Repositories\Database;

use PDO;
use Win\Application;

/**
 * Transações no Banco de Dados
 */
class Transaction
{
	/** @var PDO */
	private $pdo;

	/**
	 * Inicia a Transação
	 * @param Connection $conn
	 */
	public function __construct(Connection $conn = null)
	{
		$conn = $conn ?? Application::app()->conn;
		$this->pdo = $conn->getPdo();
		$this->pdo->beginTransaction();
	}

	/**
	 * Completa a Transação
	 */
	public function commit()
	{
		$this->pdo->commit();
	}

	/**
	 * Cancela a Transação
	 */
	public function rollback()
	{
		$this->pdo->rollBack();
	}
}