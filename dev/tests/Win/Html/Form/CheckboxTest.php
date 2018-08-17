<?php

namespace Win\Html\Form;

class CheckboxTest extends \PHPUnit_Framework_TestCase {

	private static $active = 'checked="true"';

	public function testOneParam() {
		$this->assertEquals(Checkbox::active(true), static::$active);
		$this->assertEquals(Checkbox::active(1), static::$active);
		$this->assertEquals(Checkbox::active('teste'), static::$active);
	}

	public function testIsActive() {
		$this->assertEquals(Checkbox::active('teste', 'teste'), static::$active);
	}

	public function testIsNotActive() {
		$this->assertNotEquals(Checkbox::active('teste', 'errado'), static::$active);
	}

}
