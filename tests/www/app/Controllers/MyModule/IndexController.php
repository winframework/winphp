<?php


namespace App\Controllers\MyModule;

use Win\Controllers\Controller;
use Win\Views\View;

class IndexController extends Controller
{
	public function index()
	{
		$this->title = 'Esse é meu módulo | ' . APP_NAME;

		return new View('multi/nivel/de/arquivo');
	}
}
