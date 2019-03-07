<?php

namespace Win\Calendar;

use PHPUnit\Framework\TestCase;

class TimerTest extends TestCase
{
	public function testTime()
	{
		$t = new Timer();
		$this->assertGreaterThan(0, strlen($t->time()));
	}

	public function testReset()
	{
		$t = new Timer();
		$t->reset();
		$this->assertGreaterThan(0, strlen($t->time()));
	}
}
