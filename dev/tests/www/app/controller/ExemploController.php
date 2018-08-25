<?php

namespace controller;

use Win\Mvc\Controller;
use Win\Mvc\View;

class ExemploController extends Controller {

	protected function init() {
		
	}

	public function index() {
		return new View('exemplo');
	}

}
