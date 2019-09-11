<?php

namespace Win\Mvc;

use Win\Formats\Arr\Data;
use Win\Request\Url;

/**
 * Application (WinPHP Framework)
 *
 * Framework em PHP baseado em MVC
 * Responsável por incluir as páginas de acordo com a URL e criar a estrutura MVC
 * @author winPHP Framework <http://github.com/winframework/winphp/>
 * @version 1.4.1
 */
class Application
{
	/** @var Controller */
	public $controller;

	/** @var View */
	public $view;

	protected static $instance = null;
	private $name;
	private $homePage = 'index';
	private $params = ['index', 'index'];

	/**
	 * Cria a aplicação principal
	 * @param mixed[] $data
	 */
	public function __construct($data = [])
	{
		static::$instance = $this;
		Data::instance()->load($data);
		$this->name = (string) $this->data()->get('name', '');
		$this->setParams(Router::instance()->getParams());

		$this->controller = ControllerFactory::create();
		$this->view = ViewFactory::create();
	}

	/**
	 * Define os parâmetros
	 * Se estiver vazio, utiliza os parâmetros padrão.
	 * @param string[] $params
	 */
	public function setParams($params)
	{
		$this->params = array_replace($this->params, array_filter($params));
	}

	/**
	 * Retorna o ponteiro para a aplicação principal
	 * @return static
	 */
	public static function app()
	{
		return static::$instance;
	}

	/** @return Data */
	public function data()
	{
		return Data::instance();
	}

	/**
	 * Roda a aplicação
	 * Executando o Controller e criando o Layout que contem a View
	 */
	public function run()
	{
		$this->controller->load();
		$layout = new Layout($this->controller->layout);
		$layout->load();
	}

	/** @return string */
	public function getName()
	{
		return $this->name;
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
		return Url::instance()->format(implode('/', $this->getParams()));
	}

	/**
	 * Retorna a página atual
	 * @return string
	 */
	public function getPage()
	{
		return (string) $this->params[0];
	}

	/**
	 * Retorna TRUE se está na página inicial
	 * @return bool
	 */
	public function isHomePage()
	{
		return $this->getPage() === $this->homePage;
	}

	/**
	 * Retorna TRUE se está em alguma página de erro (404, 403, 503, etc)
	 * @return bool
	 */
	public function isErrorPage()
	{
		return $this->getPage() == '404';
	}

	/**
	 * Retorna um todos os parâmetros da URL
	 * @return string[]
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * Retorna uma parte da URL
	 * @param int $index Parte escolhida
	 * @return string
	 */
	public function getParam($index)
	{
		return (key_exists($index, $this->params)) ? $this->params[$index] : '';
	}

	/**
	 * Define a página como 404
	 * @codeCoverageIgnore
	 */
	public function pageNotFound()
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
