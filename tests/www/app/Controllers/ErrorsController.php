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

	public function _404(Throwable $e)
	{
		http_response_code(404);
		$this->title = 'Página não encontrada';
		$this->error = $e;

		return new View('errors/404');
	}

	public function _500(Throwable $e)
	{
		http_response_code(500);
		$this->layout = null;
		$this->title = 'Ocorreu um erro';
		$this->error = $e;

		return new View('errors/500');
	}
}
