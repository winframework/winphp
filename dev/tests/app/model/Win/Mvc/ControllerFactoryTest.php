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

	public function testControllerIndex() {
		new Application();
		$controller = ControllerFactory::create('index');
		$notFound = $controller instanceof DefaultController;
		$this->assertFalse($notFound);
	}

	public function testAutomaticActionName() {
		new Application();
		$controller = ControllerFactory::create('index', 'my-example-action');
		$this->assertEquals('myExampleAction', $controller->getAction());
	}

}
