<?php

namespace Win\Html\Seo;

use PHPUnit\Framework\TestCase;

class DescriptionTest extends TestCase
{
	const LONG_STRING = 'My Long Description of Test of Many characters';

	public function testOtimizeDefault()
	{
		Description::$default = 'My Default';
		$this->assertEquals(
			'My Default',
			Description::otimize('')
		);
		$this->assertEquals(
			'My Short Description',
			Description::otimize('My Short Description')
		);
	}

	public function testOtimize()
	{
		$this->assertEquals(
			'My Long Description...',
			Description::otimize(static::LONG_STRING, 20)
		);

		$this->assertEquals(
			'My Long...',
			Description::otimize(static::LONG_STRING, 15)
		);
	}
}
