<?php

namespace Win\Mvc;

use Win\Mvc\Application;
use Win\Mvc\ControllerFactory;
use Win\Mvc\DefaultController;

class ControllerFactoryTest extends \PHPUnit_Framework_TestCase {

	public function testControllerNotFound() {
		new Application();
		$controller = ControllerFactory::create('this-controller-doent-exit');
		$notFound = $controller instanceof DefaultController || $controller instanceof Error404Controller;
		$this->assertTrue($notFound);
	}

	public function testCreateController() {
		new Application();
		$controller = ControllerFactory::create('demo');
		$this->assertFalse($controller instanceof IndexController);
		$this->assertTrue($controller instanceof DemoController);
	}

	public function testCreateControllerWithIndexAction() {
		$controller = ControllerFactory::create('demo', 'index');
		$this->assertFalse($controller instanceof IndexController);
		$this->assertTrue($controller instanceof DemoController);
	}

	public function testCreateControllerWithCustomAction() {
		$app = new Application();
		$controller = ControllerFactory::create('demo', 'return-five');
		$five = $controller->load();

		$this->assertEquals('returnFive', $controller->getAction());
		$this->assertEquals(5, $five);
		$this->assertFalse(404, $app->getPage());
	}

	public function testCreateControllerWithInvalidAction() {
		$app = new Application();
		$controller = ControllerFactory::create('demo', 'invalid');
		$controller->load();
		$this->assertEquals('invalid', $controller->getAction());
		$this->assertTrue(404, $app->getPage());
	}

}
