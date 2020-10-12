<?php

namespace Win\Repositories\Database;

use PDOException;
use Win\HttpException;

/**
 * Erro de Banco de dados
 */
class DbException extends HttpException
{
	public function __construct($message, $code, PDOException $previous)
	{
		parent::__construct($message, $code, $previous);
	}
}
