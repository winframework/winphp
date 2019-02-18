<?php

namespace Win\Mvc;

class ViewFactoryTest extends \PHPUnit\Framework\TestCase {

	public function testCreate_Index() {
		$defaultView = ViewFactory::create('index');
		$this->assertTrue($defaultView->exists());
	}

	public function testCreate_Index_Index() {
		$indexView = ViewFactory::create('index', 'index');
		$this->assertTrue($indexView->exists());
	}

	public function testCreate_404() {
		$view = ViewFactory::create('doent-exist');
		$this->assertTrue(!$view->exists());
	}

	public function testCreate_404_Index() {
		$view = ViewFactory::create('doent-exist', 'index');
		$this->assertFalse($view->exists());
	}

	public function testCreate_Demo_Index() {
		$view = ViewFactory::create('demo', 'index');
		$this->assertTrue($view->exists());
	}

	public function testCreate_Page_Index() {
		$view = ViewFactory::create('page', 'index');
		$this->assertTrue($view->exists());
	}

	public function testCreate_Page_Subpage() {
		$view = ViewFactory::create('page', 'subpage');
		$this->assertTrue($view->exists());
	}

	public function testCreate_Page_Error() {
		$view = ViewFactory::create('page', 'error');
		$this->assertFalse($view->exists());
	}

	public function testCreate_ErrorCode() {
		$view = ViewFactory::create(404);
		$this->assertFalse($view->exists());
	}

}
