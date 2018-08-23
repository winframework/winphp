<?php

namespace Win\Mvc;

use controller\DemoController;
use controller\IndexController;
use Win\Request\Url;

class ControllerTest extends \PHPUnit_Framework_TestCase {

	public function testExtendsController() {
		new Application();
		$controller = new DemoController();
		$five = $controller->returnFive();
		$this->assertEquals($five, 5);
	}

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

	public function getData() {
		$controller = new DefaultController();
		$controller->addData('a', 1);
		$controller->addData('b', 2);

		$this->assertEquals(1, $controller->getData('a'));
		$this->assertEquals(2, $controller->getData('b'));
		$this->assertEquals(null, $controller->getData('c'));
	}

	public function setTitle() {
		$controller = new DefaultController();
		$controller->setTitle('My title page');
		$this->assertEquals('My title page', $controller->getData('title'));
	}

	public function testActionNotFound() {
		$index = new IndexController('index');
		$index->thisActionDoentExist();
		$this->assertEquals('404', $index->app->getPage());
	}

	public function testCallInitOnLoad() {
		$demo = new DemoController();
		$demo->index();
		$this->assertNotEquals(10, $demo->getData('init'));
		$demo->load();
		$this->assertEquals(10, $demo->getData('init'));
	}

	public function testReload() {
		$demo = new DemoController();
		$demo->addData('a', 10);
		$demo->load();
		$demo->setTitle('My title 1');
		$demo->reload();
		$this->assertEquals('My Index Action', $demo->getData('title'));
		$this->assertEquals(10, $demo->getData('a'));
	}

	public function testReturnInvalidView() {
		$demo = new DemoController();
		$this->assertFalse($demo->returnInvalidView()->exists());
		$this->assertFalse($demo->returnInvalidView2()->exists());
	}

	public function testReturnValidView() {
		$demo = new DemoController();
		$this->assertTrue($demo->returnValidView()->exists());
	}

}
