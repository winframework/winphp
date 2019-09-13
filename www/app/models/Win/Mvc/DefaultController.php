<?php

namespace Win\Mvc;

use Win\Html\Seo\Title;

/**
 * Controller Padrão
 *
 * Chamado quando não há um Controller criado para a página
 */
final class DefaultController extends Controller
{
	public function index()
	{
	}

	public function load()
	{
		$this->app = Application::app();
		$this->app->view->addData(
			'title',
			Title::otimize(ucwords(str_replace('-', ' ', $this->app->getPage())))
		);
		$this->app->view->validate();
	}
}
