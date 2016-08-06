<?php

namespace Win\Mvc;

use Win\Helper\Url;
use Win\Request\Input;

/**
 * Application (WinPHP Framework)
 *
 * Framework em PHP baseado em MVC
 * Esta classe é responśavel por incluir as páginas de acordo com a URL e gerenciar a estrutura MVC
 * @author winPHP Framework <http://github.com/winframework/winphp/>
 * @version 0.1
 */
class Application {

	protected static $app = null;

	/** @var mixed[] */
	private $config;
	private $name;
	private $page;
	private $homePage = 'index';
	private $errorPageList = ['404', '401', '403', '500'];
	private $url;
	private $paramList;
	private $title;

	/** @var Controller Controller atual */
	public $controller;

	/** @var View View atual */
	public $view;

	/**
	 * Cria a aplicação principal
	 * @param mixed[] $config Configurações
	 */
	public function __construct($config = []) {
		static::$app = $this;
		$this->config = $config;
		$this->name = $this->getConfig('name', '');

		$this->setParamList(Url::instance()->getFragments());
		$this->controller = ControllerFactory::create($this->getParam(0), $this->getParam(1));

		if (Route::instance()->run()):
			$this->setParamList(Route::instance()->getCustomUrl());
			$this->controller = Route::instance()->createController();
		endif;

		$this->setPage($this->getParam(0));
		$this->view = ViewFactory::create($this->getParam(0), $this->paramList);

		$this->validatePage404();
	}

	/**
	 * Retorna um ponteiro para a aplicação principal
	 * @return static
	 */
	public static function app() {
		return static::$app;
	}

	/**
	 * Retorna uma configuração
	 * @param string $key Nome da configuração
	 * @param string $default Valor default, caso esta configuração esteja em branco
	 */
	public function getConfig($key, $default = '') {
		return (key_exists($key, $this->config)) ? $this->config[$key] : $default;
	}

	/**
	 * Executa a applicação
	 * chamando o controller e criando o layout que contem a view
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
		return $this->getBaseUrl() . $this->getUrl();
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
		if (is_null($this->url)):
			$this->url = Url::instance()->format(implode('/', $this->getParamList()));
		endif;
		return $this->url;
	}

	/** @return string */
	public function getServerName() {
		return Input::server('SERVER_NAME', FILTER_SANITIZE_STRING);
	}

	/** @return bolean */
	public function isLocalHost() {
		return (in_array($this->getServerName(), ['localhost', '127.0.0.1', '::1', null]));
	}

	/**
	 * Retorna o nome da página atual
	 * @return string
	 */
	public function getPage() {
		return $this->page;
	}

	/** @param string $page */
	protected function setPage($page) {
		$this->page = $page;
	}

	/**
	 * Retorna o nome da página inicial
	 * @return string
	 */
	public function getHomePage() {
		return $this->homePage;
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
		return (boolean) (in_array($this->page, $this->errorPageList));
	}

	/**
	 * Retorna um array com todos os parametros da URL
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
	 * Retorna o titulo da página atual
	 * @return string
	 */
	public function getTitle() {
		if (is_null($this->title)):
			$this->title = ucwords(str_replace('-', ' ', $this->page));
		endif;
		return $this->title;
	}

	/**
	 * Define o titulo da página
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * Define a página como 404
	 */
	public function pageNotFound() {
		$this->page = '404';
		$this->title = 'Página não encontrada';
		$this->view = new View('404');
		$this->controller = ControllerFactory::create('Error404');
		http_response_code(404);
		if ($this->getParam(0) !== '404'):
			$this->controller->load();
		endif;
	}

	/**
	 * Chama pageNotFound se o usuario acessar /404
	 *
	 * Isso garante que todas as funcionalidades de pageNotFound serão executadas
	 * mesmo se a página existente 404 for acessada
	 */
	private function validatePage404() {
		if ($this->getParam(0) === '404'):
			$this->pageNotFound();
		endif;
	}

}
