<?php

namespace Win\Html\Form;

class CheckboxTest extends \PHPUnit_Framework_TestCase {

	private static $active = 'checked';

	public function testIsActive() {
		$this->assertContains(static::$active, Checkbox::active('teste', 'teste'));
	}

	public function testIsActive_OneParam() {
		$this->assertContains(static::$active, Checkbox::active(true));
		$this->assertContains(static::$active, Checkbox::active(1));
		$this->assertContains(static::$active, Checkbox::active('teste'));
	}

	public function testIsNotActive() {
		$this->assertNotContains(static::$active, Checkbox::active('teste', 'errado'));
	}

}
