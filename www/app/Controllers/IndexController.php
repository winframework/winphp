<?php

namespace App\Controllers;

use Win\Controllers\Controller;
use Win\Services\Mailer;
use Win\Views\View;

class IndexController extends Controller
{

	public function __construct() {
	}

	public function index()
	{
		$this->title = 'Página Inicial | ' . APP_NAME;

		return new View('index');
	}
}
