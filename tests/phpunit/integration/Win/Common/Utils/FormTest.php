<?php

namespace Win\Common\Utils;

use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
	const CHECKED = 'checked';
	const SELECTED = 'selected';

	public function testCheck()
	{
		$this->assertNotContains(static::CHECKED, Form::check('teste', 'errado'));
		$this->assertContains(static::CHECKED, Form::check('teste', 'teste'));
		$this->assertContains(static::CHECKED, Form::check(true));
		$this->assertContains(static::CHECKED, Form::check(1));
		$this->assertContains(static::CHECKED, Form::check('teste'));
	}

	public function testSelect()
	{
		$this->assertContains(static::SELECTED, Form::select(true));
		$this->assertContains(static::SELECTED, Form::select(1));
		$this->assertContains(static::SELECTED, Form::select('teste', 'teste'));
	}
}
