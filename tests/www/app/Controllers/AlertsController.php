<?php

namespace App\Controllers;

use Win\Controllers\Controller;
use Win\Repositories\Alert;
use Win\Views\View;

class AlertsController extends Controller
{
	public function index()
	{
		Alert::error('Ops! Um erro');
		Alert::error('Outro erro');
		Alert::success('Parabéns');

		$this->redirect('alerts/show');
	}

	public function show()
	{
		return new View('alerts');
	}
}
