<?php

namespace App\Controllers;

use Win\Core\Controller;
use Win\Core\View;

class IndexController extends Controller
{
	public function index()
	{
		$this->title = 'Página Inicial | ' . APP_NAME;

		return new View('index');
	}
}
