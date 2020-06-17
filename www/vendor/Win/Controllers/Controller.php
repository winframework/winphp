<?php

namespace Win\Controllers;

use Win\Application;
use Win\Request\Url;
use Win\Common\Utils\Str;

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

	public function __toString()
	{
		$replaces = ['Controllers\\', 'Controller', 'App\\', '\\'];
		return Str::toUrl(str_replace($replaces, ' ', get_class($this)));
	}

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
		Url::redirect($this->app->getPage());
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
