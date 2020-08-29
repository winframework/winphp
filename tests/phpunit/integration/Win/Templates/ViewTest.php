<?php

namespace Win\Templates;

use PHPUnit\Framework\TestCase;
use Win\Application;
use Win\Common\DependenceInjector;
use Win\Controllers\MyController;

class ViewTest extends TestCase
{
	public static function setUpBeforeClass(): void
	{
		$app = new Application();
		$app->controller = DependenceInjector::make(MyController::class);
	}

	/**
	 * @expectedException \Win\HttpException
	 */
	public function testDoNotExist()
	{
		$view = new View('invalid-not-exist-view');
		$this->assertFalse($view->exists());
	}

	public function testExists()
	{
		$view = new View('index/index');
		$this->assertTrue($view->exists());
	}

	public function testGetTitle()
	{
		$title = 'My Title';
		Application::app()->controller->title = $title;
		$view = new View('index/index');
		$this->assertEquals($title, $view->get('title'));
	}
}
