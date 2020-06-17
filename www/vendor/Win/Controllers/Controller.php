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
	public Application $app;
	public string $layout = 'layout';
	public string $title;

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
		Url::redirect(Url::$segments[0]);
	}

	/**
	 * Redireciona para a URL
	 * @param string $url
	 * @codeCoverageIgnore
	 */
	protected function redirect($url)
	{
		Url::redirect($url);
	}

	/**
	 * Atualiza a mesma página
	 * @param string $url
	 * @codeCoverageIgnore
	 */
	protected function refresh()
	{
		Url::redirect(Url::$path);
	}
}
