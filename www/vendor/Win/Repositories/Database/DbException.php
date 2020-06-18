<?php

namespace Win\Repositories\Database;

use Exception;
use PDOException;

/**
 * Erro de Banco de dados
 */
class DbException extends Exception
{
	public function __construct($message, PDOException $previous)
	{
		parent::__construct($message, null, $previous);
	}
}
