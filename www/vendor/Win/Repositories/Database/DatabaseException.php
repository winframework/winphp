<?php

namespace Win\Repositories\Database;

use Exception;
use PDOException;

/**
 * Erro de Banco de dados
 */
class DatabaseException extends Exception
{
	public function __construct(PDOException $previous)
	{
		parent::__construct(
			'Ocorreu um erro durante a leitura/escrita de dados.',
			null,
			$previous
		);
	}
}
