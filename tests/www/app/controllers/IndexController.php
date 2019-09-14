<?php

namespace controllers;

use Win\Mvc\Controller;
use Win\Mvc\View;

class IndexController extends Controller
{
	public function index()
	{
		$this->setTitle('Página Inicial | ' . $this->app->getName());

		return new View('index');
	}
}
