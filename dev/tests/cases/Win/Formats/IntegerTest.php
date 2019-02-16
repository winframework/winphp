<?php

namespace Win\Formats;

use PHPUnit\Framework\TestCase;

class IntegerTest extends TestCase {

	public function testMinimumLength() {
		$this->assertEquals('0095', Integer::minLength(95, 4));
		$this->assertEquals('96', Integer::minLength(96, 2));
		$this->assertEquals('97', Integer::minLength(97, 1));
	}

}
