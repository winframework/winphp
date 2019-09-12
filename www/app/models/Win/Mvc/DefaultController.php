<?php

namespace Win\Mvc;

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
		Application::app()->view->validate();
	}
}
