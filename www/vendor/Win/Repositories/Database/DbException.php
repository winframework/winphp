<?php

namespace Win\Repositories\Database;

use Exception;
use PDOException;

/**
 * Erro de Banco de dados
 */
class DbException extends Exception
{
	public function __construct(PDOException $previous)
	{
		$message = 'Ocorreu um erro durante a leitura/escrita de dados.';
		parent::__construct($message, null, $previous);
	}
}
