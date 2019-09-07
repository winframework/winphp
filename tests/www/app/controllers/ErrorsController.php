<?php

namespace controllers;

use Win\Mvc\Controller;

class ErrorsController extends Controller {

	protected function init() {
		
	}

	public function index() {
		
	}

	public function error404() {
		$this->setTitle('Ops! Page not found!');
	}

}
