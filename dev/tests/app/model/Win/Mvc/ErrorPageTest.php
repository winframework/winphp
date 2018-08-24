<?php

namespace Win\Mvc;

use Win\Request\Url;

class ErrorPageTest extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		new Application();
	}

	public function testIsNotErrorPage() {
		$this->assertFalse(ErrorPage::isErrorPage());

		Url::instance()->setUrl('exemplo');
		ErrorPage::validate();
		$this->assertFalse(ErrorPage::isErrorPage());
	}

	public function testIsErrorPage() {
		ErrorPage::setError(404);
		$this->assertTrue(ErrorPage::isErrorPage());

		ErrorPage::setError(503);
		ErrorPage::validate();
		$this->assertTrue(ErrorPage::isErrorPage());

		Url::instance()->setUrl('403');
		ErrorPage::validate();
		$this->assertTrue(ErrorPage::isErrorPage());
	}

	public function testDinamicTitle() {
		Url::instance()->setUrl('inexistente');
		ErrorPage::$pages[404] = 'Ops! something is wrong.';
		ErrorPage::setError(404);

		$this->assertTrue(ErrorPage::isErrorPage());
		$this->assertEquals('Ops! something is wrong.', Application::app()->controller->getData('title'));
	}

}
