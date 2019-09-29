<?php

namespace App\Controllers;

use Win\Core\Controller;
use Win\Core\Response\ResponseException;
use Win\Core\View;

class ErrorsController extends Controller
{
	public function error404(ResponseException $e)
	{
		$this->title = 'Página não encontrada';
		$this->exception = $e;

		return new View('404');
	}
}
