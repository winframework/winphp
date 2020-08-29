<?php

namespace Win\Controllers;

use Win\Application;
use Win\Services\Router;

/**
 * Controller
 *
 * Responsável por processar as requisições e retornar a View
 */
abstract class Controller
{
	public Application $app;
	public ?string $layout = 'layout';
	protected Router $router;

	/** @var string|string[] */
	public $title;

	/**
	 * Action Init
	 * @codeCoverageIgnore
	 */
	public function init()
	{
	}

	public function __construct(Router $router)
	{
		$this->router = $router;
	}
}
