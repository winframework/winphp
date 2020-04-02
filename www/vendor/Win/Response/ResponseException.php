<?php

namespace Win\Response;

use Exception;
use Throwable;

/**
 * Resposta HTTP de Erro
 * 403, 404, 500, etc
 */
class ResponseException extends Exception
{
	public static $errorsController = 'App\\Controllers\\ErrorsController';

	/**
	 * Envia a resposta de erro
	 */
	public function sendResponse()
	{
		http_response_code($this->code);
		try {
			$destination = [static::$errorsController, 'error' . $this->code, [$this]];
			ResponseFactory::create($destination);
		} catch (Exception $e) {
			// Envia apenas um HTTP 404, sem body
		}
	}
}
