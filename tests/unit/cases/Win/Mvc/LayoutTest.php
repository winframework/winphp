<?php

namespace Win\Mvc;

use PHPUnit\Framework\TestCase;

class LayoutTest extends TestCase
{
	public function testExits()
	{
		$layout = new Layout('main');
		$layout2 = new Layout('other');
		$this->assertTrue($layout->exists());
		$this->assertTrue($layout2->exists());
	}

	public function testDoNotExits()
	{
		$layout = new Layout('inexist');
		$this->assertFalse($layout->exists());
	}

	public function testToString()
	{
		$layout = new Layout('other');
		$this->assertContains('Other example of layout', $layout->__toString());
	}
}
