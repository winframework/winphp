<?php

namespace Win;

use PDO;
use Win\Common\DependenceInjector as DI;
use Win\Common\Utils\Str;
use Win\Controllers\Controller;
use Win\Request\Url;
use Win\HttpException;
use Win\Templates\Template;
use Win\Templates\View;

/**
 * Application (WinPHP Framework)
 *
 * Framework em PHP baseado em MVC
 * Responsável por incluir as páginas de acordo com a URL e criar a estrutura MVC
 * @author winPHP Framework <http://github.com/winframework/winphp/>
 * @version 1.7.0
 */
class Application
{
	protected static Application $instance;

	public Controller $controller;
	public View $view;
	public ?PDO $pdo = null;
	public string $action = '';
	public string $page = '';

	/**
	 * Cria a aplicação principal
	 */
	public function __construct()
	{
		static::$instance = $this;
		Url::init();
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
	public function run($class, $method = 'index', ...$args)
	{
		if (!class_exists($class)) {
			throw new HttpException("Controller '{$class}' not found", 404);
		}
		/** @var Controller $controller */
		$controller = DI::make($class);
		$controller->app = $this;
		$this->controller = $controller;
		$this->action = $method;
		$this->setPage($class);

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
		if ($response instanceof View && $this->controller->layout) {
			$response = new Template($this->controller->layout, ['content' => $response]);
		}

		return $response;
	}

	/**
	 * Retorna TRUE se está na página inicial
	 * @return bool
	 */
	public function isHomePage()
	{
		return Url::$segments == Url::HOME;
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

	/**
	 * Retorna a página atual
	 * @param string
	 */
	private function setPage($class)
	{
		$replaces = ['Controllers\\', 'Controller', 'App\\', '\\'];
		$this->page = Str::toUrl(str_replace($replaces, ' ', $class));
	}
}
