<?php

namespace Win\Html\Form;

use PHPUnit\Framework\TestCase;

class CheckboxTest extends TestCase
{
	const ACTIVE = 'checked';

	public function testIsActive()
	{
		$this->assertNotContains(static::ACTIVE, Checkbox::check('teste', 'errado'));
		$this->assertContains(static::ACTIVE, Checkbox::check('teste', 'teste'));
		$this->assertContains(static::ACTIVE, Checkbox::check(true));
		$this->assertContains(static::ACTIVE, Checkbox::check(1));
		$this->assertContains(static::ACTIVE, Checkbox::check('teste'));
	}
}
