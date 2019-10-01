<?php

namespace App\Controllers;

use Win\Controllers\Controller;
use Win\Views\View;

class IndexController extends Controller
{
	public function index()
	{
		$this->title = 'Página Inicial | ' . APP_NAME;

		return new View('index');
	}
}