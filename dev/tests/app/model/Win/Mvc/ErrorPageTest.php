<?php

namespace Win\Mvc;

class ErrorPageTest extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		new Application();
	}

	public function testIsErrorPage() {
		$this->assertFalse(ErrorPage::isErrorPage());

		ErrorPage::setError(404);
		$this->assertTrue(ErrorPage::isErrorPage());

		ErrorPage::setError(503);
		$this->assertTrue(ErrorPage::isErrorPage());
	}

	public function testDinamicTitle() {
		ErrorPage::$pages[404] = 'Ops! something is wrong.';
		$this->assertFalse(ErrorPage::isErrorPage());
		ErrorPage::setError(404);
		$this->assertEquals('Ops! something is wrong.', Application::app()->controller->getData('title'));
	}

}
