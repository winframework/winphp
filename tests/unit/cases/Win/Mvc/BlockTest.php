<?php

namespace Win\Mvc;

use PHPUnit\Framework\TestCase;

class BlockTest extends TestCase
{
	const VALID = 'custom-block';
	const INVALID = 'this-file-doesnt-exit';

	public function testGetFile()
	{
		$block = new Block('main');
		$this->assertContains('main.phtml', $block->getFile());
		$block2 = new Block('main/not-exist');
		$this->assertContains('main/not-exist.phtml', $block2->getFile());
	}

	public function testParamData()
	{
		$block = new Block('index', ['a' => 5]);
		$this->assertEquals(5, $block->getData('a'));
		$block = new Block('index');
		$this->assertNotEquals(5, $block->getData('a'));
	}

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
