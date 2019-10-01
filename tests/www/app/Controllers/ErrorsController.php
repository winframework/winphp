<?php

namespace App\Controllers;

use Win\Controllers\Controller;
use Win\Response\ResponseException;
use Win\Views\View;

class ErrorsController extends Controller
{
	public function error404(ResponseException $e)
	{
		$this->title = 'Página não encontrada';
		$this->exception = $e;

		return new View('404');
	}
}
