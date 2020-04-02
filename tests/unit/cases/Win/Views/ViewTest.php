<?php

namespace Win\Views;

use PHPUnit\Framework\TestCase;
use Win\Application;
use Win\Controllers\MyController;

class ViewTest extends TestCase
{
	public static function setUpBeforeClass()
	{
		$app = new Application();
		$app->controller = new MyController(10);
	}

	/**
	 * @expectedException \Win\Response\ResponseException
	 */
	public function testDoNotExist()
	{
		$view = new View('invalid-not-exist-view');
		$this->assertFalse($view->exists());
	}

	public function testExists()
	{
		$view = new View('index');
		$this->assertTrue($view->exists());
	}

	public function testGetFile()
	{
		$view = new View('index');
		$this->assertContains('index.phtml', $view->getFile());
	}

	public function testToString()
	{
		$view = new View('index');
		$this->assertGreaterThan(0, strlen((string) $view));
	}

	public function testGetTitle()
	{
		$title = 'My Title';
		Application::app()->controller->title = $title;
		$view = new View('index');
		$this->assertEquals($title, $view->getTitle());
	}
}
