<?php

namespace Win\Common;

use PHPUnit\Framework\TestCase;

class TimerTest extends TestCase
{
	public function testGetTime()
	{
		$t = new Timer();
		$this->assertGreaterThan(0, strlen($t->getTime()));
	}

	public function testReset()
	{
		$t = new Timer();
		$t->reset();
		$this->assertGreaterThan(0, strlen($t->getTime()));
	}
}
