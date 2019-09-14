<?php

namespace Win\Mvc;

use controllers\IndexController;
use Win\Request\Url;

/**
 * Application (WinPHP Framework)
 *
 * Framework em PHP baseado em MVC
 * Responsável por incluir as páginas de acordo com a URL e criar a estrutura MVC
 * @author winPHP Framework <http://github.com/winframework/winphp/>
 * @version 1.5.0
 */
class Application
{
	/** @var Controller */
	public $controller;

	/** @var View */
	public $view;

	/** @var static */
	protected static $instance;

	/**
	 * Cria a aplicação principal
	 */
	public function __construct()
	{
		static::$instance = $this;
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
	 * Roda a aplicação
	 * Executando o Controller e exibindo a Resposta
	 */
	public function run()
	{
		$destination = Router::getDestination();
		$this->controller = ControllerFactory::create($destination);
		$action = $destination[1];
		$args = $destination[2];

		if (method_exists($this->controller, $action)) {
			$response = $this->controller->$action(...$args);
			echo $response;
		} else {
			$this->page404();
		}
	}

	/** @return string */
	public function getName()
	{
		return APP_NAME;
	}

	/** @return string */
	public function getFullUrl()
	{
		return Url::instance()->getBaseUrl() . Url::instance()->getUrl();
	}

	/** @return string */
	public function getBaseUrl()
	{
		return Url::instance()->getBaseUrl();
	}

	/**
	 * Retorna a URL Atual
	 * @return string
	 */
	public function getUrl()
	{
		return Url::instance()->getUrl();
	}

	/**
	 * Retorna a página atual
	 * @return string
	 */
	public function getPage()
	{
		return Url::instance()->getSegments()[0];
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
	 * @codeCoverageIgnore
	 */
	public function page404()
	{
		$this->errorPage(404);
	}

	/**
	 * Define a página atual como algum erro
	 * @param int $code
	 * @param string $message
	 * @throws HttpException
	 */
	public function errorPage($code, $message = '')
	{
		throw new HttpException($code, $message);
	}
}
