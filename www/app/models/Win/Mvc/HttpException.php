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
	protected static $controller = 'errors';
	protected static $runCount = 0;

	/**
	 * @param int $code
	 * @param string $message
	 */
	public function __construct($code, $message = '')
	{
		parent::__construct($message, $code);
	}

	/** Executa uma resposta HTTP de erro */
	public function run()
	{
		$error = $this->code;
		$app = Application::app();
		$app->setParams([$error, 'error' . $error]);

		$app->controller = new ErrorsController();
		$app->view = new View($error, ['title' => $error]);

		http_response_code($error);
		try {
			$app->run();
		} catch (HttpException $e) {
		}
	}
}
