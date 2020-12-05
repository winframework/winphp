<?php

namespace Win\Repositories;

use PDOException;
use Win\HttpException;

/**
 * Erro de Banco de dados
 */
class DbException extends HttpException
{
	public function __construct(string $message, int $code, PDOException $previous)
	{
		parent::__construct($message, $code, $previous);
	}
}
