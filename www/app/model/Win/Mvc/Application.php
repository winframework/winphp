<?php

namespace Win\Mvc;

use Win\Data\Config;
use Win\Request\Url;

/**
 * Application (WinPHP Framework)
 *
 * Framework em PHP baseado em MVC
 * Esta classe é responsável por incluir as páginas de acordo com a URL e gerenciar a estrutura MVC
 * @author winPHP Framework <http://github.com/winframework/winphp/>
 * @version 1.2.6
 */
class Application {

	protected static $app = null;
	private $name;
	private $page;
	private $homePage = 'index';
	private $paramList;

	/** @var Controller */
	public $controller;

	/** @var View */
	public $view;

	/**
	 * Cria a aplicação principal
	 * @param mixed[] $config
	 */
	public function __construct($config = []) {
		static::$app = $this;
		Config::load($config);
		$this->name = (string) Config::get('name', '');

		$this->setParamList(Url::instance()->getFragments());
		$this->controller = ControllerFactory::create($this->getParam(0), $this->getParam(1));

		Router::instance()->load();
		if (Router::instance()->run()):
			$this->setParamList(Router::instance()->getCustomUrl());
			$this->controller = Router::instance()->createController();
		endif;

		$this->setPage($this->getParam(0));
		$this->view = ViewFactory::create($this->getParam(0), $this->paramList);

		ErrorPage::validate();
	}

	/**
	 * Retorna o ponteiro para a aplicação principal
	 * @return static
	 */
	public static function app() {
		return static::$app;
	}

	/**
	 * Roda a aplicação
	 * Executando o Controller e criando o Layout que contem a View
	 */
	public function run() {
		$this->controller->load();
		$layout = new Layout($this->controller->layout);
		$layout->load();
	}

	/** @return string */
	public function getName() {
		return $this->name;
	}

	/** @return string */
	public function getFullUrl() {
		return Url::instance()->getBaseUrl() . Url::instance()->getUrl();
	}

	/** @return string */
	public function getBaseUrl() {
		return Url::instance()->getBaseUrl();
	}

	/**
	 * Retorna a URL Atual
	 * @return string
	 */
	public function getUrl() {
		return Url::instance()->format(implode('/', $this->getParamList()));
	}

	/**
	 * Retorna a página atual
	 * @return string
	 */
	public function getPage() {
		return $this->page;
	}

	/** @param string $page */
	public function setPage($page) {
		$this->page = $page;
	}

	/**
	 * Retorna TRUE se está na página inicial
	 * @return boolean
	 */
	public function isHomePage() {
		return (boolean) ($this->page === $this->homePage);
	}

	/**
	 * Retorna TRUE se está em alguma página de erro (404, 403, 503, etc)
	 * @return boolean
	 */
	public function isErrorPage() {
		return ErrorPage::isErrorPage();
	}

	/**
	 * Retorna um todos os parâmetros da URL
	 * @return string[]
	 */
	protected function getParamList() {
		return $this->paramList;
	}

	/**
	 * Define os parâmetros.
	 * Se estiver vazio, utiliza os parâmetros padrão.
	 * @param string[] $paramList
	 */
	private function setParamList($paramList) {
		$paramDefaulf = [$this->homePage, 'index'];
		$this->paramList = array_replace($paramDefaulf, array_filter($paramList));
	}

	/**
	 * Retorna uma parte da URL
	 * @param int $position Parte escolhida
	 * @return string
	 */
	public function getParam($position) {
		return (key_exists($position, $this->paramList)) ? $this->paramList[$position] : '';
	}

	/**
	 * Redireciona para a URL
	 * @param string $url
	 */
	public function redirect($url = '') {
		Url::instance()->redirect($url);
	}

	/**
	 * Atualiza a mesma página
	 * @param string $url
	 */
	public function refresh() {
		Url::instance()->redirect($this->getUrl());
	}

	/** Define a página como 404 */
	public function pageNotFound() {
		$this->errorPage(404);
	}

	/**
	 * Define a página atual como algum erro
	 * @param int $errorCode [401, 404, 500, etc]
	 */
	public function errorPage($errorCode) {
		ErrorPage::setError($errorCode);
	}

}
