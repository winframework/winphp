<?php

namespace controllers;

use Win\Mvc\Controller;
use Win\Mvc\View;

class IndexController extends Controller
{
	public function index()
	{
		$this->title = 'Página Inicial | ' . APP_NAME;

		return new View('index');
	}
}
