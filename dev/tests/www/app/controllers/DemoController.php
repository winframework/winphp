<?php

namespace controller;

use Win\Mvc\Controller;
use Win\Mvc\View;

class DemoController extends Controller {

	protected function init() {
		parent::init();
		$this->addData('init', 10);
	}

	public function index() {
		$this->setTitle('My Index Action');
		return new View('index');
	}

	public function returnFive() {
		return 5;
	}

	public function returnValidView() {
		return new View('my-view');
	}

	public function returnInvalidView() {
		return new View('this-file-doesnt-exist');
	}

	public function returnInvalidView2() {
		return new View('my-view/invalid');
	}

	public function tryRedirect() {
		$this->redirect('exemplo');
	}

	public function tryRefresh() {
		$this->refresh();
	}

}
