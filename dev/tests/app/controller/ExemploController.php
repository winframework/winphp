<?php

namespace controller;

use Win\Mvc\Controller;
use Win\Mvc\View;

class ExemploController extends Controller {

	public function index() {
		return new View('exemplo');
	}

}
