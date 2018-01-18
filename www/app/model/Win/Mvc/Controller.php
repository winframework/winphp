<?php

namespace Win\Mvc;

use Win\Helper\Url;

/**
 * Controllers
 * 
 * São responsáveis por processar as requisições e definir as Views
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

	/** @var string */
	public $layout = 'main';

	/** @var mixed[] Array variaveis para usar na View */
	private $data = [];

	/**
	 * Cria o Controller, definindo o action
	 */
	public function __construct($action = '') {
		$this->app = Application::app();
		$this->setAction($action);
	}


	public function setTitle($title) {
		$this->addData('title', $title);
	}

	/**
	 * Adiciona uma variavel para usar na view
	 * @param string $name
	 * @param mixed $value
	 */
	public function addData($name, $value) {
		$this->data[$name] = $value;
	}

	/**
	 * Retorna uma variavel do controller
	 * @param string $name
	 * @return mixed|null
	 */
	public function getData($name) {
		if (key_exists($name, $this->data)) {
			return $this->data[$name];
		}
		return null;
	}

	/**
	 * Define o action
	 * @param string $action
	 */
	private function setAction($action = '') {
		if (empty($action)) {
			$action = $this->app->getParam(1);
		}
		$this->action = $this->toCamelCase($action);
	}

	/**
	 * Retorna o nome do action em camelCase
	 * @param string $action
	 * @return string
	 */
	private function toCamelCase($action) {
		if (strpos($action, '-') !== false) {
			$camelCaseName = str_replace(' ', '', ucwords(str_replace('-', ' ', $action)));
			$camelCaseName[0] = strtolower($camelCaseName[0]);
			$action = $camelCaseName;
		}
		return $action;
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

		if ($view instanceof View && !$this->app->isErrorPage()):
			$this->app->view = $view;
		endif;

		if (!$this->app->isErrorPage()):
			$this->app->view->mergeData($this->data);
			$this->app->view->validate();
		endif;
	}

	public function reload() {
		$this->init();
		$this->index();
		$this->app->view->mergeData($this->data);
	}

	/**
	 * Volta para o método index da pagina atual
	 */
	protected function backToIndex() {
		if (!$this->app->isErrorPage()):
			Url::instance()->redirect($this->app->getPage());
		endif;
	}

	/**
	 * Action Index
	 */
	abstract public function index();

	/**
	 * Este metodo é chamado sempre que o controller é carregado
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
