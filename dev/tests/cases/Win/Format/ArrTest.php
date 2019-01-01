<?php

namespace Win\Format;

use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase {

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

	public function testIsAssoc() {
		$this->assertTrue(Arr::isAssoc(['a' => 'Apple', 'b' => 'Ball', 'c' => 'Car']));
		$this->assertTrue(Arr::isAssoc([1 => 'a', 2 => 'b', 5 => 'c']));
		$this->assertTrue(Arr::isAssoc([0 => 'a', 1 => 'b', 5 => 'c']));
		$this->assertTrue(Arr::isAssoc([1 => 'a', 2 => 'b', 3 => 'c']));
	}

	public function testIsNotAssoc() {
		$this->assertFalse(Arr::isAssoc(['a', 'b', 'c']));
		$this->assertFalse(Arr::isAssoc([0 => 'a', 1 => 'b', 2 => 'c']));
	}

}
