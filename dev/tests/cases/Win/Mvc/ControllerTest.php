<?php

namespace Win\Mvc;

use controllers\DemoController;
use controllers\IndexController;
use PHPUnit\Framework\TestCase;
use Win\Request\Header;
use Win\Request\Url;

class ControllerTest extends TestCase {

	public function testExtendsController() {
		new Application();
		$controller = new DemoController();
		$five = $controller->returnFive();
		$this->assertEquals($five, 5);
	}

	public function testGetAction() {
		Url::instance()->setUrl('my-page/example-action');
		new Application();
		$controller = new IndexController('myAction');
		$controller2 = new IndexController('my-action');
		$controller3 = new IndexController();

		$this->assertEquals('myAction', $controller->getAction());
		$this->assertEquals('my-action', $controller2->getAction());
		$this->assertEquals('index', $controller3->getAction());
	}

	public function testGetData() {
		$controller = new DefaultController();
		$controller->addData('a', 1);
		$controller->addData('b', 2);

		$this->assertEquals(1, $controller->getData('a'));
		$this->assertEquals(2, $controller->getData('b'));
		$this->assertEquals(null, $controller->getData('c'));
	}

	public function testSetTitle() {
		$controller = new DefaultController();
		$controller->setTitle('My title page');
		$this->assertEquals('My title page', $controller->getData('title'));
	}

	/**
	 * @expectedException \Win\Mvc\HttpException
	 */
	public function testActionNotFound() {
		$index = new IndexController('index');
		$index->thisActionDoentExist();
		$this->assertEquals('404', $index->app->getPage());
	}

	public function testCallInitOnLoad() {
		$demo = ControllerFactory::create('Demo', 'index');
		$demo->index();
		$this->assertNotEquals(10, $demo->getData('init'));
		$demo->load();
		$this->assertEquals(10, $demo->getData('init'));
	}

	public function testCall_Current404() {
		$app = new Application();
		$app->setPage(404);
		$demo = new DemoController('index');
		$demo->teste();
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

	public function testRedirect() {
		$demo = new DemoController();
		$demo->tryRedirect();
		return Header::instance()->clear();
	}

	public function testRefresh() {
		$demo = new DemoController();
		$demo->tryRefresh();
		return Header::instance()->clear();
	}

}
