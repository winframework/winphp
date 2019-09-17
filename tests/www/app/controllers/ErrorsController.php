<?php

namespace controllers;

use Win\Mvc\Controller;
use Win\Mvc\View;
use Win\Response\ResponseException;

class ErrorsController extends Controller
{
	public function error404(ResponseException $e)
	{
		$this->title = 'Página não encontrada';
		$this->exception = $e;

		return new View('404');
	}
}
