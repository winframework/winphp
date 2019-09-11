<?php

namespace controllers;

use Win\FlashMessage\Alert;
use Win\Mvc\Controller;
use Win\Mvc\View;

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
