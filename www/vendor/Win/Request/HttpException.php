<?php

namespace Win\Request;

use App\Controllers\ErrorsController;
use Exception;

/**
 * Erro Http
 * 403, 404, 500, etc
 */
class HttpException extends Exception
{
	public static $controller = ErrorsController::class;

	/**
	 * Envia a resposta de erro
	 */
	public function run()
	{
		http_response_code($this->code);
		try {
			Router::process([static::$controller, "error{$this->code}", [$this]]);
		} catch (Exception $e) {
			// Envia apenas um HTTP 404, sem body
		}
	}
}
