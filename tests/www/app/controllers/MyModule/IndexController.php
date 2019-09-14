<?php

namespace controllers\MyModule;

use Win\Mvc\Controller;
use Win\Mvc\View;

class IndexController extends Controller
{
	public function index()
	{
		$this->title = 'Esse é meu módulo | ' . $this->app->getName();

		return new View('index');
	}
}
