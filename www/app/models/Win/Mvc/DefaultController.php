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
		Application::app()->view->validate();
	}
}
