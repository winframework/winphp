<?php

namespace Win\Repositories\Database;

use PDO;

/**
 * Transações no Banco de Dados
 */
class Transaction
{
	/** @var PDO */
	private $pdo;

	/**
	 * Inicia a Transação
	 * @param Orm $orm
	 */
	public function __construct(Orm $orm)
	{
		$this->pdo = $orm->conn->getPdo();
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
