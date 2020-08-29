<?php

namespace App\Controllers;

use Throwable;
use Win\Controllers\Controller;
use Win\Templates\View;

/**
 * Páginas de Erros 
 * 404, 500, etc
 */
class ErrorsController extends Controller
{
	/** @var Throwable */
	public $error;

	public function init()
	{
		http_response_code(str_replace('_', '', $this->router->action));
	}

	public function _404(Throwable $e)
	{
		$this->title = 'Página não encontrada';
		$this->error = $e;

		return new View('errors/404');
	}

	public function _500(Throwable $e)
	{
		$this->layout = null;
		$this->title = 'Ocorreu um erro';
		$this->error = $e;

		return new View('errors/500');
	}
}
