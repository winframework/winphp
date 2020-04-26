<?php

namespace Win\Common\Utils;

use PHPUnit\Framework\TestCase;

class IntegerTest extends TestCase
{
	public function testMinLength()
	{
		$this->assertEquals('0095', Integer::minLength(95, 4));
		$this->assertEquals('96', Integer::minLength(96, 2));
		$this->assertEquals('97', Integer::minLength(97, 1));
	}

	public function testFormatId()
	{
		$this->assertEquals('000095', Integer::formatId(95));
	}
}
