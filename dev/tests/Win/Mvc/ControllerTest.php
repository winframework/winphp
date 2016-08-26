<?php

namespace Win\Mvc;

use controller\IndexController;
use Win\Helper\Url;

class ControllerTest extends \PHPUnit_Framework_TestCase {

	public function testGetAction() {
		$controller = new IndexController('myAction');
		$this->assertEquals('myAction', $controller->getAction());

		$controller2 = new IndexController('my-action');
		$this->assertEquals('myAction', $controller2->getAction());

		Url::instance()->setUrl('my-page/example-action');
		new Application();
		$controller3 = new IndexController();
		$this->assertEquals('exampleAction', $controller3->getAction());
	}

	public function testActionNotFound() {
		$index = new IndexController('index');
		$index->thisActionDoentExist();
		$this->assertEquals('404', $index->app->getPage());
	}

}
