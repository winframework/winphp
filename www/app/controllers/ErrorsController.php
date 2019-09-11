<?php

namespace controllers;

use Win\Mvc\Controller;

class ErrorsController extends Controller
{
	public function error404()
	{
		$this->setTitle('Page not found!');
	}
}
