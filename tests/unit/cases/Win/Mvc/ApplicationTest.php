<?php

namespace Win\Mvc;

use PHPUnit\Framework\TestCase;
use Win\Formats\Arr\Data;
use Win\Request\Server;
use Win\Request\Url;

class ApplicationTest extends TestCase
{
	const URL = 'demo/my-action/second-param/3rd-param/';

	/** @var Application */
	private static $app;

	public static function setUpBeforeClass()
	{
		static::$app = static::newApp(static::URL);
	}

	public static function newApp($url = 'index')
	{
		Url::instance()->setUrl($url);

		return new Application();
	}

	public function testApp()
	{
		$this->assertTrue(static::$app instanceof Application);
		$this->assertTrue(Application::app() instanceof Application);
	}

	public function testData()
	{
		$name = Application::app()->getName();
		Data::instance()->set('a', 10);

		$this->assertEquals($name, Application::app()->data()->get('name'));
		$this->assertEquals(10, Application::app()->data()->get('a'));
	}

	public function testGetName()
	{
		$config = ['name' => 'My custom Application Name'];
		$app = new Application($config);
		$this->assertEquals('My custom Application Name', $app->getName());
	}

	public function testNewConstruct()
	{
		$app = new Application(['name' => 'Other']);
		$this->assertNotEquals($app->getName(), static::$app->getName());
	}

	public function testGetPage()
	{
		$this->assertEquals('demo', static::$app->getPage());

		$app = static::newApp('index');
		$this->assertEquals('index', $app->getPage());
	}

	public function testGetAction()
	{
		$this->assertEquals('myAction', static::$app->controller->getAction());
		$app = static::newApp('contato');
		$this->assertEquals('index', $app->controller->getAction());
	}

	public function testGetParam()
	{
		$this->assertEquals(static::$app->getPage(), static::$app->getParam(0));
		$this->assertEquals('my-action', static::$app->getParam(1));
		$this->assertEquals('second-param', static::$app->getParam(2));
		$this->assertEquals('3rd-param', static::$app->getParam(3));
		$this->assertEquals('', static::$app->getParam(4));

		$app = static::newApp('');
		$this->assertEquals('index', $app->getParam(1));
		$this->assertNotEquals('', $app->getParam(1));
		$this->assertEquals('', $app->getParam(2));
	}

	public function testIsHomePage()
	{
		$this->assertFalse(static::$app->isHomePage());
		$app = static::newApp('index');
		$this->assertTrue($app->isHomePage());
	}

	public function testIsLocalHost()
	{
		$this->assertEquals(true, Server::isLocalHost());
	}

	public function testGetUrl()
	{
		static::newApp(static::URL);
		$this->assertEquals(static::URL, static::$app->getUrl());
		$this->assertNotEquals(static::URL, static::$app->getFullUrl());
		$this->assertContains(static::URL, static::$app->getFullUrl());
	}

	public function testGetUrlWithRoutes()
	{
		$app = static::newApp('other-page/params/');
		$this->assertEquals('Demo/index/params/', $app->getUrl());
		$this->assertNotEquals('demo/index/params/', $app->getFullUrl());
		$this->assertContains('other-page/params/', $app->getFullUrl());
	}

	public function testRun()
	{
		ob_start();
		$app = static::newApp('index/index');
		$app->run();
		$this->assertEquals('index', $app->getPage());
		ob_end_clean();
	}

	/**
	 * @expectedException \Win\Mvc\HttpException
	 */
	public function testRun404()
	{
		static::$app->run();
		$this->assertTrue(static::$app->isErrorPage());
	}

	/**
	 * @expectedException \Win\Mvc\HttpException
	 */
	public function testErrorPage500()
	{
		static::$app->errorPage(500);
		$this->assertEquals('500', static::$app->getPage());
	}

	/**
	 * @expectedException \Win\Mvc\HttpException
	 */
	public function testPageNotFound()
	{
		static::$app->pageNotFound();
		$this->assertEquals('404', static::$app->getPage());
	}
}
