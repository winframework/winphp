<?php

namespace Win\Response;

use App\Controllers\ErrorsController;
use Exception;
use Throwable;

/**
 * Resposta HTTP de Erro
 * 403, 404, 500, etc
 */
class ResponseException extends Exception
{
	/**
	 * Envia a resposta de erro
	 */
	public function sendResponse()
	{
		http_response_code($this->code);
		try {
			ResponseFactory::send([ErrorsController::class, "error{$this->code}", [$this]]);
		} catch (Exception $e) {
			// Envia apenas um HTTP 404, sem body
		}
	}
}
