<?php

namespace Win\Html\Form;

class CheckboxTest extends \PHPUnit_Framework_TestCase {

	private static $active = 'checked';

	public function testIsActive() {
		$this->assertContains(static::$active, Checkbox::check('teste', 'teste'));
	}

	public function testIsActive_OneParam() {
		$this->assertContains(static::$active, Checkbox::check(true));
		$this->assertContains(static::$active, Checkbox::check(1));
		$this->assertContains(static::$active, Checkbox::check('teste'));
	}

	public function testIsNotActive() {
		$this->assertNotContains(static::$active, Checkbox::check('teste', 'errado'));
	}

}
