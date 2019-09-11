<?php

namespace Win\Mvc;

use PHPUnit\Framework\TestCase;

class ViewFactoryTest extends TestCase
{
	public function testCreateIndex()
	{
		ApplicationTest::newApp('index');
		$this->assertTrue(ViewFactory::create()->exists());

		ApplicationTest::newApp('index/index');
		$this->assertTrue(ViewFactory::create()->exists());
	}

	public function testCreate404()
	{
		ApplicationTest::newApp('not-exist');
		$this->assertFalse(ViewFactory::create()->exists());
	}

	public function testCreateDemo()
	{
		ApplicationTest::newApp('demo/index');
		$this->assertTrue(ViewFactory::create()->exists());
	}

	// public function testCreatePage()
	// {
	// 	ApplicationTest::newApp('page');
	// 	$this->assertTrue(ViewFactory::create()->exists());
	// }

	// public function testCreateSubPage()
	// {
	// 	ApplicationTest::newApp('page/subpage');
	// 	$this->assertTrue(ViewFactory::create()->exists());
	// }

	public function testCreateSubPageError()
	{
		ApplicationTest::newApp('page/error');
		$this->assertFalse(ViewFactory::create()->exists());
	}
}
