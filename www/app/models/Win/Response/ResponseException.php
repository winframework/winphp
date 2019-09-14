<?php

namespace Win\Response;

use Exception;
use Win\Mvc\Application;

/**
 * Resposta HTTP de Erro
 * 403, 404, 500, etc
 */
class ResponseException extends Exception
{
	/**
	 * @param string $message
	 * @param int $code
	 */
	public function __construct($message, $code)
	{
		parent::__construct($message, $code);
	}

	/**
	 * Envia a resposta de erro
	 */
	public function sendResponse()
	{
		http_response_code($this->code);
		try {
			$destination = ['ErrorsController', 'error' . $this->code];
			$response = ResponseFactory::create($destination);
			Application::app()->controller->exception = $this;
			echo $response;
		} catch (Exception $e) {
		}
	}
}
