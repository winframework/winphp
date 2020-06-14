<?php

namespace Win\Controllers;

use Win\Application;
use Win\Request\Url;

/**
 * Controller
 *
 * Responsável por processar as requisições e retornar a View
 */
abstract class Controller
{
	/** @var Application */
	public $app;

	/** @var string */
	public $layout = 'layout';

	/** @var string */
	public $title;

	/**
	 * Action Init
	 * @codeCoverageIgnore
	 */
	public function init()
	{
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
