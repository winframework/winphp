<?php

namespace Win;

use Win\DI;
use Win\Controllers\Controller;
use Win\HttpException;
use Win\Services\Router;
use Win\Templates\Template;
use Win\Templates\View;
use Win\Utils\Str;

/**
 * Application (WinPHP Framework)
 *
 * Framework em PHP baseado em MVC
 * Responsável por incluir as páginas de acordo com a URL e criar a estrutura MVC
 * @author winPHP Framework http://github.com/winframework/winphp/
 * @version 1.9.1
 */
class Application
{
	protected static Application $instance;

	public Controller $controller;
	public Router $router;
	public View $view;

	/**
	 * Cria a aplicação principal
	 */
	public function __construct()
	{
		static::$instance = $this;
		$this->router = Router::instance();
	}

	/**
	 * Retorna o ponteiro para a aplicação principal
	 * @return static
	 */
	public static function app()
	{
		return static::$instance;
	}

	/**
	 * Executa o [Controller, action] e envia o retorno como resposta
	 * @param string $class Controller
	 * @param string $method Action
	 * @param array $args
	 */
	public function run($class, $method = 'index', ...$args)
	{
		if (!class_exists($class)) {
			throw new HttpException("Controller '{$class}' não encontrado", 404);
		}
		/** @var Controller $controller */
		$controller = DI::instance($class);
		$controller->app = $this;
		$this->controller = $controller;
		$this->router->page = $this->getPage();
		$this->router->action = $method;

		if (!method_exists($controller, $method)) {
			throw new HttpException("Action '{$method}' não encontrado em '{$class}'", 404);
		}

		$controller->init();
		$response = $controller->$method(...$args);
		echo $this->send($response);
	}

	/**
	 * Envia a resposta baseado no tipo
	 * @param mixed $response
	 * @return mixed
	 * @codeCoverageIgnore
	 */
	private function send($response)
	{
		if (is_array($response)) {
			header('Content-Type: application/json');
			return json_encode($response);
		}
		if ($response instanceof View) {
			$this->view = $response;
			if ($this->controller->layout) {
				return new Template($this->controller->layout, ['content' => $response]);
			}
		}

		return $response;
	}

	/**
	 * Retorna a página atual
	 * @return string
	 */
	protected function getPage()
	{
		$replaces = ['Controllers\\', 'Controller', 'App\\', '\\'];
		return Str::toUrl(str_replace($replaces, ' ', get_class($this->controller)));
	}
}
