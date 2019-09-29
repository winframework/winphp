<?php

namespace Win\Core\Repositories\Database;

use Exception;

/**
 * Errors
 */
class DatabaseException extends Exception
{
	public function __construct($previous)
	{
		$message = 'Ocorreu um erro durante a leitura/escrita de dados.';
		parent::__construct($message, null, $previous);
	}
}
