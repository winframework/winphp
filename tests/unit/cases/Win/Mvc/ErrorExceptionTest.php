<?php

namespace Win\Mvc;

use controllers\IndexController;
use PHPUnit\Framework\TestCase;
use Win\Request\Url;

class ErrorExceptionTest extends TestCase
{
	public static function setUpBeforeClass()
	{
		Url::instance()->setUrl('index/index');
		new Application();
		Application::app()->controller = new IndexController('index');
		Application::app()->view = new View('index');
	}

	public function testGetCode()
	{
		$e = new ErrorResponse(402);
		$this->assertEquals(402, $e->getCode());
	}

	public function testGetMessage()
	{
		$e = new ErrorResponse(402, 'Hãm?');
		$this->assertEquals('Hãm?', $e->getMessage());
	}

	public function testRunIndex()
	{
		$e = new ErrorResponse(402);
		ob_start();
		$e->run();
		ob_end_clean();
		$this->assertEquals('404', Application::app()->getPage());
		$this->assertTrue(Application::app()->isErrorPage());
	}

	public function testIsErrorCode()
	{
		$this->assertTrue(ErrorResponse::isErrorCode(404));
		$this->assertTrue(ErrorResponse::isErrorCode(500));
		$this->assertFalse(ErrorResponse::isErrorCode('teste'));
	}
}
