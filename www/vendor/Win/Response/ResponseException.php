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
	 * @param string $message
	 * @param int $code
	 * @param Throwable $previous
	 */
	public function __construct($message, $code, $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}

	/**
	 * Envia a resposta de erro
	 */
	public function sendResponse()
	{
		http_response_code($this->code);
		try {
			$destination = [static::$errorsController, 'error' . $this->code, [$this]];
			$response = ResponseFactory::create($destination);
			echo $response;
		} catch (Exception $e) {
			// Envia apenas um 404 padrão
		}
	}
}
