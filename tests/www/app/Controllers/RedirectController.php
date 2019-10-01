<?php

namespace App\Controllers;

use Win\Controllers\Controller;
use Win\Repositories\Alert;
use Win\Views\View;

class RedirectController extends Controller
{
	public function index()
	{
		Alert::success('Você será redirecionado');
		$this->redirect('alerts/show');

		Alert::error('Este não pode aparecer');

		return new View('index');
	}
}
