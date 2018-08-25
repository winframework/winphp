<?php

namespace controller;

class IndexController extends \Win\Mvc\Controller {

	protected function init() {

	}

	public function index() {
		$this->setTitle('Página Inicial | ' . $this->app->getName());
	}

}
