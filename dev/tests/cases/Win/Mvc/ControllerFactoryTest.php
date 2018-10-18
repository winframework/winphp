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

	public function testCreateController_IndexAction() {
		$controller = ControllerFactory::create('demo');
		$this->assertEquals('index', $controller->getAction());
	}

	public function testCreateController_UnderscoreAction() {
		$controller = ControllerFactory::create('demo', '_test');
		$this->assertEquals('_test', $controller->getAction());
	}

	public function testCreateController_CustomAction() {
		$app = new Application();
		$app->view = new View('index');
		$controller = ControllerFactory::create('demo', 'return-five');
		$controller->load();

		$this->assertEquals('returnFive', $controller->getAction());
	}

	/**
	 * @expectedException Win\Mvc\HttpException
	 */
	public function testCreateController_InvalidAction() {
		$app = new Application();
		$controller = ControllerFactory::create('demo', 'invalid');
		$controller->load();
		$this->assertEquals('invalid', $controller->getAction());
		$this->assertFalse($app->view->exists());
	}

	/**
	 * @expectedException Win\Mvc\HttpException
	 */
	public function testCreateController_InvalidView() {
		$controller = ControllerFactory::create('demo', 'return-invalid-view');
		$controller->load();
	}

	public function testLoad_View() {
		Url::instance()->setUrl('index');
		$app = new Application();
		$controller = ControllerFactory::create('demo', 'return-valid-view');
		$controller->load();
		$this->assertTrue($app->view->exists());
	}

}
