<?php

namespace Win;

use App\Controllers\IndexController;
use Win\Controllers\Controller;
use Win\Repositories\Database\Connection;
use Win\Request\Router;
use Win\Request\Url;
use Win\HttpException;
use Win\Views\View;

/**
 * Application (WinPHP Framework)
 *
 * Framework em PHP baseado em MVC
 * Responsável por incluir as páginas de acordo com a URL e criar a estrutura MVC
 * @author winPHP Framework <http://github.com/winframework/winphp/>
 * @version 1.5.1
 */
class Application
{
	/** @var Controller */
	public $controller;

	/** @var View */
	public $view;

	/** @var Connection */
	public $conn;

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
	 * Roda a aplicação e envia a resposta
	 */
	public function run()
	{
		Router::process(Router::getDestination());
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
