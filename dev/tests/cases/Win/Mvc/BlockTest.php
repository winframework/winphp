<?php

namespace Win\Mvc;

use PHPUnit\Framework\TestCase;

class BlockTest extends TestCase
{
	const VALID = 'custom-block';
	const INVALID = 'this-file-doesnt-exit';

	public function testExist()
	{
		$this->assertTrue((new Block(static::VALID))->exists());
		$this->assertFalse((new Block(static::INVALID))->exists());
	}

	public function testData()
	{
		$b = new Block(static::VALID);
		$b->addData('a', 1);
		$b->addData('b', 2);
		$this->assertEquals(1, $b->getData('a'));
		$this->assertEquals(2, $b->getData('b'));
	}

	public function testDataDoNotExist()
	{
		$b = new Block((static::INVALID));
		$b->addData('a', 1);
		$b->addData('b', 2);
		$this->assertEquals(1, $b->getData('a'));
		$this->assertEquals(2, $b->getData('b'));
	}

	public function testToString()
	{
		$b = new Block(static::VALID);
		$c = new Block(static::INVALID);
		$this->assertEquals('My custom block HTML', $b->toString());
		$this->assertEquals('My custom block HTML', (string) $b);
		$this->assertEquals('', $c->toString());
	}
}
