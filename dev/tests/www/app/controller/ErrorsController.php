<?php

namespace controller;

use Win\Mvc\Controller;

class ErrorsController extends Controller {

	protected function init() {
		
	}

	public function index() {
		
	}

	public function _404() {
		$this->setTitle('Ops! Page not found!');
	}

}
