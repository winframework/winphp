<?php

namespace Win\Mvc;

use controller\DemoController;
use controller\IndexController;
use Win\Mvc\Application;
use Win\Mvc\ControllerFactory;
use Win\Mvc\DefaultController;
use Win\Request\Url;

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
		new Application();
		$controller = ControllerFactory::create('demo', 'return-five');
		$controller->load();

		$this->assertEquals('returnFive', $controller->getAction());
	}

	public function testCreateControllerWithInvalidView() {
		$app = new Application();
		$controller = ControllerFactory::create('demo', 'return-invalid-view');
		$controller->load();

		$this->assertFalse($app->view->exists());
	}

	public function testCreateControllerWithInvalidAction() {
		$app = new Application();
		$controller = ControllerFactory::create('demo', 'invalid');
		$controller->load();
		$this->assertEquals('invalid', $controller->getAction());
		$this->assertEquals(404, $app->getPage());
		$this->assertFalse($app->view->exists());
	}

	public function testLoadWithView() {
		Url::instance()->setUrl('index');
		$app = new Application();
		$controller = ControllerFactory::create('demo', 'return-valid-view');
		$controller->load();
		$this->assertTrue($app->view->exists());
	}

}
