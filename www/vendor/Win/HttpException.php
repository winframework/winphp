<?php

namespace Win;

use Exception;

/**
 * Erro Http
 * 403, 404, 500, etc
 */
class HttpException extends Exception
{
	public function __construct($message, $code, $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
