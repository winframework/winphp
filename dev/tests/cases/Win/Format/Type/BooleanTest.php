<?php

namespace Win\Format\Type;

use PHPUnit\Framework\TestCase;

class BooleanTest extends TestCase {

	public function testToString_Sim() {
		$this->assertEquals('sim', Boolean::toString(true));
		$this->assertEquals('sim', Boolean::toString(1));
	}

	public function testToString_Nao() {
		$this->assertEquals('não', Boolean::toString(false));
		$this->assertEquals('não', Boolean::toString(0));
		$this->assertEquals('não', Boolean::toString(null));
		$this->assertEquals('não', Boolean::toString(''));
	}

}
