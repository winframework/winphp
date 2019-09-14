<?php

namespace Win\Mvc;

use controllers\ErrorsController;
use Exception;

/**
 * Resposta HTTP de Erro
 * 403, 404, 500, etc
 */
class HttpException extends Exception
{
	protected static $controller = 'codes';
	protected static $runCount = 0;

	/**
	 * @param int $code
	 * @param string $message
	 */
	public function __construct($code, $message = '')
	{
		parent::__construct($message, $code);
	}

	/**
	 * Executa uma resposta HTTP de erro
	 */
	public function run()
	{
		$code = $this->code;
		$app = Application::app();

		$app->controller = new ErrorsController();
		$app->controller->action = 'error' . $code;
		http_response_code($code);
		try {
			$app->run();
		} catch (HttpException $e) {
		}
	}
}
