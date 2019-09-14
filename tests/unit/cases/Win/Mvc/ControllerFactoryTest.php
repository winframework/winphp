<?php

namespace Win\Mvc;

use controllers\DemoController;
use controllers\IndexController;
use PHPUnit\Framework\TestCase;
use Win\Request\Url;

class ControllerFactoryTest extends TestCase
{
	public function testControllerNotFound()
	{
		new Application();
		$controller = ControllerFactory::create('this-controller-doent-exit');
		$notFound = $controller instanceof DefaultController || $controller instanceof Error404Controller;
		$this->assertTrue($notFound);
	}

	public function testCreateController()
	{
		new Application();
		$controller = ControllerFactory::create('demo');
		$this->assertNotInstanceOf(IndexController::class, $controller);
		$this->assertInstanceOf(DemoController::class, $controller);
	}

	public function testAction()
	{
		$controller = ControllerFactory::create('demo');
		$this->assertEquals('index', $controller->getAction());
		$controller = ControllerFactory::create('demo', '_test');
		$this->assertEquals('_test', $controller->getAction());
	}

	public function testCustomAction()
	{
		$app = new Application();
		$app->view = new View('index');
		$controller = ControllerFactory::create('demo', 'return-five');
		$controller->load();

		$this->assertEquals('returnFive', $controller->getAction());
	}

	/**
	 * @expectedException \Win\Mvc\ErrorResponse
	 */
	public function testInvalidAction()
	{
		$app = new Application();
		$controller = ControllerFactory::create('demo', 'invalid');
		$controller->load();
		$this->assertEquals('invalid', $controller->getAction());
		$this->assertFalse($app->view->exists());
	}

	/**
	 * @expectedException \Win\Mvc\ErrorResponse
	 */
	public function testInvalidView()
	{
		$controller = ControllerFactory::create('demo', 'return-invalid-view');
		$controller->load();
	}

	public function testLoadView()
	{
		Url::instance()->setUrl('index');
		$app = new Application();
		$controller = ControllerFactory::create('demo', 'return-valid-view');
		$controller->load();
		$this->assertTrue($app->view->exists());
	}
}
