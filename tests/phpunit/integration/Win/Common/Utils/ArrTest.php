<?php

namespace Win\Common\Utils;

use PHPUnit\Framework\TestCase;

class ArrTest extends TestCase
{
	public function testIstFirst()
	{
		$this->assertFalse(Arr::isFirst(1));
		$this->assertFalse(Arr::isFirst(-1));
		$this->assertTrue(Arr::isFirst(0));
	}

	public function testIsLast()
	{
		$arr = ['A', 'B', 'C'];
		$length = count($arr) - 1;

		$this->assertFalse(Arr::isLast(0, $arr));
		$this->assertFalse(Arr::isLast(4, $arr));
		$this->assertTrue(Arr::isLast($length, $arr));
	}

	public function testIsAssoc()
	{
		$arrNotAssoc = ['Dog', 'Cat', 'Bird'];
		$arrAssoc1 = [3 => 'Dog', 'Cat', 'Bird'];
		$arrAssoc2 = ['Dog', 'c' => 'Cat', 'Bird'];

		$this->assertFalse(Arr::isAssoc($arrNotAssoc));
		$this->assertTrue(Arr::isAssoc($arrAssoc1));
		$this->assertTrue(Arr::isAssoc($arrAssoc2));
	}
}
