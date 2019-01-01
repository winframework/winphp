<?php

namespace Win\Calendar;

class TimerTest extends \PHPUnit\Framework\TestCase {

	public function testTime() {
		$t = new Timer();
		$this->assertFalse(empty($t->time()));
	}

	public function testReset() {
		$t = new Timer();
		$t->reset();
		$this->assertFalse(empty($t->time()));
	}

}
