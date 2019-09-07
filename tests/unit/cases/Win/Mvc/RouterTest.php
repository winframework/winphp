<?php

namespace Win\Mvc;

use controllers\DemoController;
use PHPUnit\Framework\TestCase;
use Win\Request\Url;

class RouterTest extends TestCase
{
	public function testNoCustomUrl()
	{
		new Application();
		Url::instance()->setUrl('my-page/test/');
		Router::$file = 'file-inexistent.php';
		Router::instance()->load();
		Router::instance()->run();

		$this->assertFalse(Router::instance()->hasCustomUrl());
		$this->assertInstanceOf(
			DefaultController::class,
			Router::instance()->createController()
		);
		$this->assertEquals(null, Router::instance()->getCustomUrl()[0]);
		$this->assertEquals(null, Router::instance()->getCustomUrl()[1]);
	}

	public function testNoCustomUrlByFile()
	{
		Url::instance()->setUrl('test-page/test/');
		Router::$file = '/app/config/routes.php';
		Router::instance()->load();
		Router::instance()->run();

		$this->assertFalse(Router::instance()->hasCustomUrl());
		$this->assertInstanceOf(
			DefaultController::class,
			Router::instance()->createController()
		);
	}

	public function testNoCustomUrlByInvalidFile()
	{
		Url::instance()->setUrl('other-page/test/');
		Router::$file = '/app/config/invalid.php';
		Router::instance()->load();
		Router::instance()->run();

		$this->assertFalse(Router::instance()->hasCustomUrl());
		$this->assertInstanceOf(
			DefaultController::class,
			Router::instance()->createController()
		);
	}

	public function testNoCustomUrlByLoad()
	{
		Url::instance()->setUrl('mytest/haha');
		$routes = ['my-pager/(.*)' => 'exemplo/index/$1'];
		Router::instance()->load($routes);
		Router::instance()->run();

		$this->assertFalse(Router::instance()->hasCustomUrl());
		$this->assertInstanceOf(
			DefaultController::class,
			Router::instance()->createController()
		);
	}

	public function testCustomUrlByLoad()
	{
		Url::instance()->setUrl('my-pager/haha');
		$routes = ['my-pager/(.*)' => 'demo/index/$1'];
		Router::instance()->load($routes);
		Router::instance()->run();

		$this->assertTrue(Router::instance()->hasCustomUrl());
		$this->assertInstanceOf(
			DemoController::class,
			Router::instance()->createController()
		);
		$this->assertEquals('demo', Router::instance()->getCustomUrl()[0]);
		$this->assertEquals('index', Router::instance()->getCustomUrl()[1]);
	}

	public function testCustomUrlBegin()
	{
		Url::instance()->setUrl('my-pager/haha');
		$routes = ['^my-pager/(.*)' => 'demo/index/$1'];
		Router::instance()->load($routes);
		Router::instance()->run();
		$this->assertTrue(Router::instance()->hasCustomUrl());
	}

	public function testCustomUrlEnd()
	{
		Url::instance()->setUrl('test-my-pager/haha');
		$routes = ['my-pager/(.*)' => 'demo/index/$1'];
		Router::instance()->load($routes);
		Router::instance()->run();
		$this->assertTrue(Router::instance()->hasCustomUrl());
	}

	public function testNoCustomUrlEnd()
	{
		Url::instance()->setUrl('test-my-pager/haha');
		$routes = ['^my-pager/(.*)' => 'demo/index/$1'];
		Router::instance()->load($routes);
		Router::instance()->run();
		$this->assertFalse(Router::instance()->hasCustomUrl());
	}

	public function testCustomUrlByFile()
	{
		Url::instance()->setUrl('other-page/test/');
		Router::$file = '/app/config/routes.php';
		Router::instance()->load();
		Router::instance()->run();

		$this->assertTrue(Router::instance()->hasCustomUrl());
		$this->assertInstanceOf(
			DemoController::class,
			Router::instance()->createController()
		);
	}
}