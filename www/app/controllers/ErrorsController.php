<?php

namespace controllers;

use Win\Mvc\Controller;
use Win\Mvc\View;

class ErrorsController extends Controller
{
	public function error404()
	{
		$this->title = 'Page not found!';

		return new View('404');
	}
}
