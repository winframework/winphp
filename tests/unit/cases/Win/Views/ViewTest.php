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
		$this->assertTrue(strlen((string) $view) > 0);
	}

	public function testGetTitle()
	{
		$view = new View('index', ['title' => 'My title']);
		$this->assertEquals('My title', $view->getTitle());
	}
}
