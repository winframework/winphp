<?php

namespace Win\Mvc;

use Win\Request\Url;
use Win\Mvc\Application;
use Win\Request\Server;

class ApplicationTest extends \PHPUnit_Framework_TestCase {

	const URL = 'my-page/my-action/second-param/3rd-param/';

	/** @var Application */
	private static $app;

	public static function setUpBeforeClass() {
		static::$app = static::newApp(static::URL);
	}

	public static function newApp($url = 'index') {
		Url::instance()->setUrl($url);
		return new Application();
	}

	public function testApp() {
		$this->assertTrue(static::$app instanceof Application);
		$this->assertTrue(Application::app() instanceof Application);
	}

	public function testGetName() {
		$config = ['name' => 'My custom Application Name'];
		$app = new Application($config);
		$this->assertEquals('My custom Application Name', $app->getName());
	}

	public function testNewConstruct() {
		$app = new Application(['name' => 'Other']);
		$this->assertNotEquals($app->getName(), static::$app->getName());
	}

	public function testGetPage() {
		$this->assertEquals('my-page', static::$app->getPage());
	}

	public function testGetPage_Index() {
		$app = static::newApp('index');
		$this->assertEquals('index', $app->getPage());
	}

	public function testGetAction() {
		$this->assertEquals('myAction', static::$app->controller->getAction());
	}

	public function testGetAction_Index() {
		$app = static::newApp('contato');
		$this->assertEquals('index', $app->controller->getAction());
	}

	public function testGetParam() {
		$this->assertEquals(static::$app->getPage(), static::$app->getParam(0));
		$this->assertEquals('my-action', static::$app->getParam(1));
		$this->assertEquals('second-param', static::$app->getParam(2));
		$this->assertEquals('3rd-param', static::$app->getParam(3));
		$this->assertEquals('', static::$app->getParam(4));
	}

	public function testGetParam_Default() {
		$app = static::newApp('');
		$this->assertEquals('index', $app->getParam(1));
		$this->assertNotEquals('', $app->getParam(1));
		$this->assertEquals('', $app->getParam(2));
	}

	public function testIsNotHomePage() {
		$this->assertFalse(static::$app->isHomePage());
	}

	public function testIsHomePage() {
		$app = static::newApp('index');
		$this->assertTrue($app->isHomePage());
	}

	public function testIsLocalHost() {
		$this->assertEquals(true, Server::isLocalHost());
	}

	public function testGetUrl() {
		static::newApp(static::URL);
		$this->assertEquals(static::URL, static::$app->getUrl());
		$this->assertNotEquals(static::URL, static::$app->getFullUrl());
		$this->assertContains(static::URL, static::$app->getFullUrl());
	}

	public function testGetUrlWithRoutes() {
		$app = static::newApp('other-page/params/');
		$this->assertEquals('exemplo/index/params/', $app->getUrl());
		$this->assertNotEquals('exemplo/index/params/', $app->getFullUrl());
		$this->assertContains('other-page/params/', $app->getFullUrl());
	}

	public function testRun() {
		ob_start();
		$app = static::newApp('index/index');
		$app->run();
		$this->assertEquals('index', $app->getPage());
		ob_end_clean();
	}

	/**
	 * @expectedException \Win\Mvc\HttpException
	 */
	public function testRun_404() {
		static::$app->run();
		$this->assertTrue(static::$app->isErrorPage());
	}

	/**
	 * @expectedException \Win\Mvc\HttpException
	 */
	public function testErrorPage_500() {
		static::$app->errorPage(500);
		$this->assertEquals('500', static::$app->getPage());
	}

	/**
	 * @expectedException \Win\Mvc\HttpException
	 */
	public function testPageNotFound() {
		static::$app->pageNotFound();
		$this->assertEquals('404', static::$app->getPage());
	}

}
