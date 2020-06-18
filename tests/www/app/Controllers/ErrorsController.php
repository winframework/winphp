<?php

namespace App\Controllers;

use Exception;
use Win\Common\Template;
use Win\Controllers\Controller;
use Win\Views\View;

/**
 * Páginas de Erros 
 * 404, 500, etc
 */
class ErrorsController extends Controller
{
	/** @var Exception */
	public $exception;

	public function error404(Exception $e)
	{
		$this->title = 'Página não encontrada';
		$this->exception = $e;

		return new View('errors/404');
	}

	public function error503(Exception $e)
	{
		$this->title = 'Ocorreu um erro';
		$this->exception = $e;
		$this->layout = null;
		return new View('errors/503');
	}
}
