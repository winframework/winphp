<?php

namespace App\Controllers;

use Win\Controllers\Controller;
use Win\HttpException;
use Win\Views\View;

/**
 * Páginas de Erros 
 * 404, 500, etc
 */
class ErrorsController extends Controller
{
	/** @var HttpException */
	public $exception;

	public function error404(HttpException $e)
	{
		$this->title = 'Página não encontrada';
		$this->exception = $e;

		return new View('errors/404');
	}
}
