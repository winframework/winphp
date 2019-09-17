<?php

namespace controllers;

use Win\Notifications\Alert;
use Win\Mvc\Controller;
use Win\Mvc\View;

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
