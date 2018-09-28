<?php

namespace Win\Mvc;

class newTest extends \PHPUnit_Framework_TestCase {

	public function testGetCode() {
		$e = new HttpException(402);
		$this->assertEquals(402, $e->getCode());
	}

	public function testGetMessage() {
		$e = new HttpException(402, 'Hãm?');
		$this->assertEquals('Hãm?', $e->getMessage());
	}

	public function testRun() {
		$app = new Application();
		$app->controller = new \controller\DemoController();
		$e = new HttpException(402);
		$e->run();
		$this->assertEquals('404', $app->getPage());
	}

	public function testIsErrorCode() {
		$this->assertTrue(HttpException::isErrorCode(404));
		$this->assertTrue(HttpException::isErrorCode(500));
		$this->assertFalse(HttpException::isErrorCode('teste'));
	}

}
