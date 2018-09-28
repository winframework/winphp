<?php

namespace Win\Mvc;

use Win\Request\Url;
use Win\Mvc\Application;
use Win\Request\Server;

class ApplicationTest extends \PHPUnit_Framework_TestCase {

	public function testGetInstanceApp() {
		Url::instance()->setUrl('index');
		$app = new Application();
		$this->assertTrue($app instanceof Application);
		$this->assertTrue(Application::app() instanceof Application);
	}

	public function testGetName() {
		$config = ['name' => 'My custom Application Name'];
		$app = new Application($config);

		$this->assertEquals('My custom Application Name', $app->getName());
		$this->assertEquals($app->getName(), $app->getName());
	}

	public function testGetPage() {
		Url::instance()->setUrl('my-page/my-action/first-param/2nd-param/3rd-param');
		$app = new Application();

		$this->assertEquals('my-page', $app->getPage());
	}

	public function testGetParamDefault() {
		Url::instance()->setUrl('my-page');
		$app = new Application();

		$this->assertEquals('index', $app->getParam(1));
		$this->assertNotEquals('', $app->getParam(1));
	}

	public function testGetAction() {
		Url::instance()->setUrl('my-page/my-action/first-param/2nd-param/3rd-param');
		$app = new Application();

		$this->assertEquals('myAction', $app->controller->getAction());
	}

	public function testGetParam() {
		Url::instance()->setUrl('my-page/my-action/second-param/3rd-param');
		$app = new Application();

		$this->assertEquals('my-page', $app->getParam(0));
		$this->assertEquals('my-action', $app->getParam(1));
		$this->assertEquals('second-param', $app->getParam(2));
		$this->assertEquals('3rd-param', $app->getParam(3));
		$this->assertEquals('', $app->getParam(4));
	}

	public function testIsHomePage() {
		Url::instance()->setUrl('');
		$app = new Application();
		$this->assertTrue($app->isHomePage());

		Url::instance()->setUrl('index');
		$app2 = new Application();
		$this->assertTrue($app2->isHomePage());
	}

	public function testIsNotHomePage() {
		Url::instance()->setUrl('other-page');
		$app2 = new Application();
		$this->assertFalse($app2->isHomePage());
	}

	public function testIsLocalHost() {
		$this->assertEquals(true, Server::isLocalHost());
	}

	public function testGetUrl() {
		$currentUrl = Url::instance()->format('my-page/my-action/param1/param2');
		Url::instance()->setUrl($currentUrl);
		$app = new Application();
		$this->assertEquals($currentUrl, $app->getUrl());
		$this->assertNotEquals($currentUrl, $app->getFullUrl());
		$this->assertContains($currentUrl, $app->getFullUrl());
	}

	public function testGetUrlWithRoutes() {
		$currentUrl = Url::instance()->format('other-page/params/');
		Url::instance()->setUrl($currentUrl);
		$app = new Application();
		$this->assertEquals('exemplo/index/params/', $app->getUrl());
		$this->assertNotEquals('exemplo/index/params/', $app->getFullUrl());
		$this->assertContains('other-page/params/', $app->getFullUrl());
	}

	public function testRun() {
		Url::instance()->setUrl('index');
		$app = new Application();
		$app->run();
		$this->assertFalse($app->isErrorPage());
	}

	/**
	 * @expectedException Win\Mvc\HttpException
	 */
	public function testIsErrorPage() {
		Url::instance()->setUrl('404');
		$app = new Application();
		$app->run();
		$this->assertTrue($app->isErrorPage());
	}

	/**
	 * @expectedException Win\Mvc\HttpException
	 */
	public function testErrorPage_500() {
		$app = new Application();
		$app->errorPage(500);
		$this->assertEquals('500', $app->getPage());
	}

	/**
	 * @expectedException Win\Mvc\HttpException
	 */
	public function testPageNotFound() {
		$app = new Application();
		$app->pageNotFound();
		$this->assertEquals('404', $app->getPage());
	}

}
