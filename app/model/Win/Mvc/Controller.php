<?php

namespace Win\Mvc;

use Win\Helper\Url;

/**
 * Controllers
 * 
 * São responsáveis por processar as requisições e chamar as Views
 */
abstract class Controller {

	public static $dir = '/app/controller/';

	/**
	 * Ponteiro para Aplicação Principal
	 * @var Application
	 */
	public $app;

	/** @var string */
	private $action;

	/**
	 * Define qual bloco será usado como layout
	 * @var string
	 */
	public $layout = 'main';

	/**
	 * Cria o Controller, definindo o action
	 */
	public function __construct($action = '') {
		$this->app = Application::app();
		$this->setAction($action);
	}

	/**
	 * Define o action
	 * Alterando de "hifem-case" para "camelCase"
	 * @param string $action
	 */
	private function setAction($action = '') {
		if (empty($action)) {
			$action = $this->app->getParam(1);
		}

		if (strpos($action, '-') !== false) {
			$camelCaseName = str_replace(' ', '', ucwords(str_replace('-', ' ', $action)));
			$camelCaseName[0] = strtolower($camelCaseName[0]);
			$action = $camelCaseName;
		}
		$this->action = $action;
	}

	/** @return string */
	public function getAction() {
		return $this->action;
	}

	/**
	 * Carrega o controller,
	 * executando o action atual
	 */
	public function load() {
		$this->init();
		$action = $this->action;
		$view = $this->$action();
		if ($view instanceof View):
			$this->app->view = $view;
		endif;
		$this->app->view->validate();
	}

	/**
	 * Volta para o método index da pagina atual
	 */
	protected function backToIndex() {
		Url::instance()->redirect($this->app->getPage());
	}

	/**
	 * Acao index
	 */
	abstract public function index();

	/**
	 * Este metodo é chamado sempre que o controller é carregado
	 * Não é necessário fazer uso do parent::init()
	 */
	protected function init() {
		
	}

	/**
	 * Evita chamada de um metodo que nao existe
	 * @param string $name
	 * @param mixed[] $arguments
	 * @return boolean
	 */
	public function __call($name, $arguments) {
		$this->app->pageNotFound();
	}

}
