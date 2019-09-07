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
	protected static $instance = null;
	private $name;
	private $page;
	private $homePage = 'index';
	private $params;

	/** @var Controller */
	public $controller;

	/** @var View */
	public $view;

	/**
	 * Cria a aplicação principal
	 * @param mixed[] $data
	 */
	public function __construct($data = [])
	{
		static::$instance = $this;
		Data::instance()->load($data);
		$this->name = (string) $this->data()->get('name', '');
		$this->init();
	}

	/**
	 * Inicia com as Configurações básicas
	 */
	protected function init()
	{
		$this->setParams(Url::instance()->getSegments());
		$params = $this->getParams();
		$this->controller = ControllerFactory::create($params[0], $params[1]);

		Router::instance()->load();
		if (Router::instance()->run()) {
			$this->setParams(Router::instance()->getCustomUrl());
			$this->controller = Router::instance()->createController();
		}

		$this->setPage($params[0]);
		$this->view = ViewFactory::create($params[0], $params[1]);
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
		return (string) $this->page;
	}

	/** @param string $page */
	public function setPage($page)
	{
		$this->page = (string) $page;
	}

	/**
	 * Retorna TRUE se está na página inicial
	 * @return bool
	 */
	public function isHomePage()
	{
		return (bool) ($this->page === $this->homePage);
	}

	/**
	 * Retorna TRUE se está em alguma página de erro (404, 403, 503, etc)
	 * @return bool
	 */
	public function isErrorPage()
	{
		return HttpException::isErrorCode($this->getPage());
	}

	/**
	 * Retorna um todos os parâmetros da URL
	 * @return string[]
	 */
	protected function getParams()
	{
		return $this->params;
	}

	/**
	 * Define os parâmetros
	 * Se estiver vazio, utiliza os parâmetros padrão.
	 * @param string[] $params
	 */
	private function setParams($params)
	{
		$defaultParam = [$this->homePage, 'index'];
		$this->params = array_replace($defaultParam, array_filter($params));
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