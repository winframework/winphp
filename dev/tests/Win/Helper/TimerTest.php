<?php

namespace Win\Helper;

class TimerTest extends \PHPUnit_Framework_TestCase {

	public function testTime() {
		$t = new Timer();
		$this->assertTrue(strlen($t->time()) > 0);
	}

	public function testReset() {
		$t = new Timer();
		$t->reset();
		$this->assertTrue(strlen($t->time()) > 0);
	}

}
