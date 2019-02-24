<?php

namespace Win\Html\Seo;

use PHPUnit\Framework\TestCase;
use Win\Mvc\Application;

class KeywordsTest extends TestCase
{
	public static function setUpBeforeClass()
	{
		new Application();
	}

	public function testToKeys()
	{
		$string = Keywords::otimize(['first', 'Second', 'THIRD']);
		$this->assertEquals('first, second, third', $string);

		$string = Keywords::otimize(['first', 'SE-cond', 'third'], 16);
		$this->assertEquals('first, se-cond', $string);
	}
}
