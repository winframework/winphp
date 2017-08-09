<?php

namespace Win\Mvc;

use Win\Authentication\User;
use Win\Helper\Url;

/**
 * Application (WinPHP Framework)
 *
 * Framework em PHP baseado em MVC
 * Esta classe é responśavel por incluir as páginas de acordo com a URL e gerenciar a estrutura MVC
 * @author winPHP Framework <http://github.com/winframework/winphp/>
 * @version 1.2.0
 */
class Application {

	protected static $app = null;

	/** @var mixed[] */
	private $config;
	private $name;
	private $page;
	private $homePage = 'index';
	private $errorPageList = [
		404 => 'Página não encontrada',
		401 => 'Não autorizado',
		403 => 'Acesso negado',
		500 => 'Erro no Servidor',
		503 => 'Problemas de Conexão'
	];
	private $url;
	private $paramList;

	/** @var Controller Controller atual */
	public $controller;

	/** @var View View atual */
	public $view;

	/** @var User */
	private $user = null;

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

		$this->validateErrorPage();
	}

	/**
	 * Retorna um ponteiro para a aplicação principal
	 * @return static
	 */
	public static function app() {
		return static::$app;
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

	/**
	 * Retorna o usuário atual
	 * @return User
	 */
	public function getUser() {
		if (is_null($this->user)):
			$this->user = User::getCurrentUser();
		endif;
		return $this->user;
	}

	/**
	 * Retorna uma configuração
	 * @param string $key Nome da configuração
	 * @param string $default Valor default, caso esta configuração esteja em branco
	 */
	public function getConfig($key, $default = '') {
		return (key_exists($key, $this->config)) ? $this->config[$key] : $default;
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
		if (is_null($this->url)):
			$this->url = Url::instance()->format(implode('/', $this->getParamList()));
		endif;
		return $this->url;
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
		return (boolean) (key_exists((int) $this->page, $this->errorPageList));
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

	/**
	 * Chama pageNotFound se o usuario acessar /404
	 *
	 * Isso garante que todas as funcionalidades de pageNotFound serão executadas
	 * mesmo se a página existente 404 for acessada
	 */
	private function validateErrorPage() {
		if (key_exists((int) $this->getParam(0), $this->errorPageList)):
			$this->pageNotFound();
		endif;
	}

	/** Define a página como 404 */
	public function pageNotFound() {
		$this->errorPage(404);
	}

	/**
	 * Define a página como "$errorCode"
	 * @param int $errorCode [401, 404, 500, etc]
	 */
	public function errorPage($errorCode) {
		if (key_exists($errorCode, $this->errorPageList)):
			$this->stopControllerIf403($errorCode);
			$this->page = (string) $errorCode;
			$this->view = new View($errorCode);

			$this->controller = ControllerFactory::create('Error' . $errorCode);
			$this->view->addData('title', $this->errorPageList[$errorCode]);
			http_response_code($errorCode);
			$this->controller->reload();
		endif;
	}

	/**
	 * Trava o carregamento do controller, se ele definir um erro 403
	 * Isso evita que códigos sem permissões de acesso nunca sejam executados
	 * @param int $errorCode
	 */
	private function stopControllerIf403($errorCode) {
		if ($errorCode == 403 && $this->getParam(0) !== (string) $errorCode):
			$this->redirect(403 . '/index/' . $this->getUrl());
		endif;
	}

}
