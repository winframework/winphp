<?php

namespace Win\Mvc;

use PHPUnit\Framework\TestCase;

class ViewFactoryTest extends TestCase
{
	public function testCreateIndex()
	{
		$defaultView = ViewFactory::create('index');
		$this->assertTrue($defaultView->exists());

		$indexView = ViewFactory::create('index', 'index');
		$this->assertTrue($indexView->exists());
	}

	public function testCreate404()
	{
		$view = ViewFactory::create('not-exist');
		$this->assertTrue(!$view->exists());

		$view = ViewFactory::create('not-exist', 'index');
		$this->assertFalse($view->exists());

		$view = ViewFactory::create(404);
		$this->assertFalse($view->exists());
	}

	public function testCreateDemo()
	{
		$view = ViewFactory::create('demo', 'index');
		$this->assertTrue($view->exists());
	}

	public function testCreatePage()
	{
		$view = ViewFactory::create('page', 'index');
		$this->assertTrue($view->exists());

		$view = ViewFactory::create('page', 'subpage');
		$this->assertTrue($view->exists());

		$view = ViewFactory::create('page', 'error');
		$this->assertFalse($view->exists());
	}
}
