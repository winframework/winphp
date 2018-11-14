<?php

namespace Win\Format;

use PHPUnit_Framework_TestCase;

class IntegerTest extends PHPUnit_Framework_TestCase {

	public function testMinimumLength() {
		$this->assertEquals('0095', Integer::minLength(95, 4));
		$this->assertEquals('96', Integer::minLength(96, 2));
		$this->assertEquals('97', Integer::minLength(97, 1));
	}

}
