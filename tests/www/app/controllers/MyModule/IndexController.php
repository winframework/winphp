<?php

namespace controllers\MyModule;

use Win\Mvc\Controller;
use Win\Mvc\View;

class IndexController extends Controller
{
	public function index()
	{
		$this->title = 'Esse é meu módulo | ' . APP_NAME;

		return new View('multi/nivel/de/arquivo');
	}
}
