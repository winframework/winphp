<?php

namespace Win\Mvc;

use Win\Request\Url;

/**
 * Controller
 *
 * Responsável por processar as requisições e definir a View
 */
abstract class Controller
{
	public static $dir = '/app/controllers';

	/**
	 * Ponteiro para Aplicação Principal
	 * @var Application
	 */
	public $app;

	/** @var string */
	public $template = 'main';

	/** @var string */
	public $action;

	/**
	 * Variáveis para serem usadas no arquivo da View
	 * @var mixed[]
	 */
	private $data = [];

	/** @param string $title */
	public function setTitle($title)
	{
		$this->addData('title', $title);
	}

	/**
	 * Adiciona uma variável para usar na View
	 * @param string $name
	 * @param mixed $value
	 */
	public function addData($name, $value)
	{
		$this->data[$name] = $value;
	}

	/**
	 * Retorna uma variável do Controller
	 * @param string $name
	 * @return mixed|null
	 */
	public function getData($name)
	{
		if (key_exists($name, $this->data)) {
			return $this->data[$name];
		}

		return null;
	}

	/** @param View|mixed $view */
	protected function setView($view)
	{
		if ($view instanceof View) {
			$this->app->view = $view;
		}
		$this->app->view->validate();
		$this->app->view->mergeData($this->data);
	}

	/**
	 * Carrega o Controller,
	 * executando o Action atual
	 */
	public function load()
	{
		$this->app = Application::app();

		$action = $this->action;

		if (method_exists($this, $action)) {
			$view = $this->$action();
			$this->setView($view);
		} else {
			$this->app->page404();
		}
	}

	/**
	 * Volta para o método index da pagina atual
	 * @codeCoverageIgnore
	 */
	protected function backToIndex()
	{
		Url::instance()->redirect($this->app->getPage());
	}

	/**
	 * Action Index
	 */
	public function index()
	{
	}

	/**
	 * Redireciona para a URL
	 * @param string $url
	 * @codeCoverageIgnore
	 */
	public function redirect($url)
	{
		Url::instance()->redirect($url);
	}

	/**
	 * Atualiza a mesma página
	 * @param string $url
	 * @codeCoverageIgnore
	 */
	public function refresh()
	{
		Url::instance()->redirect(Url::instance()->getUrl());
	}

	public function getPage()
	{
		return $p;
	}
}
