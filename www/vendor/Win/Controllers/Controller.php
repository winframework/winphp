<?php

namespace Win\Controllers;

use Win\Application;

/**
 * Controller
 *
 * Responsável por processar as requisições e retornar a View
 */
abstract class Controller
{
	public Application $app;
	public ?string $layout = 'layout';

	/** @var string|string[] */
	public $title;

	/**
	 * Ação Inicial, executada antes de qualquer action
	 * @codeCoverageIgnore
	 */
	public function init()
	{
	}
}
