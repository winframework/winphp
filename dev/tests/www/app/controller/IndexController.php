<?php

namespace controller;

class IndexController extends \Win\Mvc\Controller {

	public function index() {
		$this->setTitle('Página Inicial | ' . $this->app->getName());
	}

}
