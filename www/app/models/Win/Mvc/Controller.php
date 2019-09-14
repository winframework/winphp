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

	/** @var Application */
	public $app;

	/** @var string */
	public $template = 'main';

	/**
	 * Action Index
	 */
	public function index()
	{
	}

	/**
	 * Adiciona uma variável para usar na View
	 * @param string $name
	 * @param mixed $value
	 */
	protected function addData($name, $value)
	{
		$this->data[$name] = $value;
	}

	/**
	 * Retorna uma variável do Controller
	 * @param string $name
	 * @return mixed|null
	 */
	protected function getData($name)
	{
		if (key_exists($name, $this->data)) {
			return $this->data[$name];
		}

		return null;
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
	 * Redireciona para a URL
	 * @param string $url
	 * @codeCoverageIgnore
	 */
	protected function redirect($url)
	{
		Url::instance()->redirect($url);
	}

	/**
	 * Atualiza a mesma página
	 * @param string $url
	 * @codeCoverageIgnore
	 */
	protected function refresh()
	{
		Url::instance()->redirect(Url::instance()->getUrl());
	}
}
