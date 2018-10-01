<?php

namespace Win\Format;

use PHPUnit_Framework_TestCase;

class ArrTest extends PHPUnit_Framework_TestCase {

	public function testIsFirst() {
		$this->assertTrue(Arr::isFirst(0));
	}

	public function testIsFirst_False() {
		$this->assertFalse(Arr::isFirst(5));
	}

	public function testIsLast() {
		$this->assertTrue(Arr::isLast(2, ['a', 'b', 'c']));
	}

	public function testIsLast_False() {
		$this->assertFalse(Arr::isLast(0, ['a', 'b', 'c']));
		$this->assertFalse(Arr::isLast(5, ['a', 'b', 'c']));
	}

}
