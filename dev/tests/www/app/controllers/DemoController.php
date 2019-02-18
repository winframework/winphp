<?php

namespace controllers;

use Win\Mvc\Controller;
use Win\Mvc\View;

/**
 * Usado pelo PHPUnit
 */
class DemoController extends Controller {

	protected function init() {
		parent::init();
		$this->addData('init', 10);
	}

	public function index() {
		$this->setTitle('My Index Action');
		return new View('demo');
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
		$this->redirect('index');
	}

	public function tryRefresh() {
		$this->refresh();
	}

}
