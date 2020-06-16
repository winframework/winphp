<?php

namespace Win;

use App\Controllers\IndexController;
use Win\Common\DependenceInjector as DI;
use Win\Controllers\Controller;
use Win\Repositories\Database\Connection;
use Win\Request\Url;
use Win\HttpException;
use Win\Repositories\Session;
use Win\Views\View;

/**
 * Application (WinPHP Framework)
 *
 * Framework em PHP baseado em MVC
 * Responsável por incluir as páginas de acordo com a URL e criar a estrutura MVC
 * @author winPHP Framework <http://github.com/winframework/winphp/>
 * @version 1.6.0
 */
class Application
{
	protected static Application $instance;

	public Controller $controller;
	public View $view;
	public Session $session;
	public ?Connection $conn = null;

	/**
	 * Cria a aplicação principal
	 */
	public function __construct()
	{
		static::$instance = $this;
		Url::init();
		$this->session = new Session();
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
	 * Executa o Controller@action e envia o retorno como resposta
	 * @param string $class Controller
	 * @param string $method Action
	 * @param array $args
	 */
	public function run($class, $method, $args = [])
	{
		if (!class_exists($class)) {
			throw new HttpException("Controller '{$class}' not found", 404);
		}
		/** @var Controller $controller */
		$controller = DI::make($class);
		$controller->app = $this;
		$this->controller = $controller;

		if (!method_exists($controller, $method)) {
			throw new HttpException("Action '{$method}' not found in '{$class}'", 404);
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

		return $response;
	}

	/**
	 * Retorna a página atual
	 * @return string
	 */
	public function getPage()
	{
		return Url::$segments[0];
	}

	/**
	 * Retorna TRUE se está na página inicial
	 * @return bool
	 */
	public function isHomePage()
	{
		return $this->controller instanceof IndexController;
	}

	/**
	 * Define a página como 404
	 * @param string $message
	 * @throws HttpException
	 */
	public function page404($message = '')
	{
		throw new HttpException($message, 404);
	}

	/**
	 * Define a página atual como algum erro
	 * @param int $code
	 * @param string $message
	 * @throws HttpException
	 */
	public function errorPage($code, $message = '')
	{
		throw new HttpException($message, $code);
	}
}
