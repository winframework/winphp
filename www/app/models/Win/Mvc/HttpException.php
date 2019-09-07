<?php

namespace Win\Mvc;

use Exception;

/**
 * Resposta HTTP de Erro
 * 403, 404, 500, etc
 */
class HttpException extends Exception
{
	protected static $controller = 'errors';

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
		$app = Application::app();
		$app->setPage($this->code);
		$app->view = new View($this->code, ['exception' => $this]);
		$action = 'error' . $this->code;
		$controller = ControllerFactory::create(static::$controller, $action);

		if (get_class($controller) !== get_class($app->controller)) {
			$app->controller = $controller;
		}

		http_response_code($this->code);
		$this->retry();
	}

	/** Define 404 caso definiu um erro inválido */
	protected function retry()
	{
		try {
			Application::app()->run();
		} catch (HttpException $e) {
			if (!$this->is404()) {
				$e->run();
			}
		}
	}

	/**
	 * Retorna TRUE se a página é 404
	 * @return bool
	 */
	private function is404()
	{
		return '404' == Application::app()->getPage();
	}

	/**
	 * Retorna TRUE se $code é um código de erro
	 * @param mixed $code
	 * @return bool
	 */
	public static function isErrorCode($code)
	{
		return (int) $code > 0;
	}
}