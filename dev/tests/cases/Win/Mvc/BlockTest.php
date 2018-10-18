<?php

namespace Win\Mvc;

class BlockTest extends \PHPUnit_Framework_TestCase {

	protected static $validFile = 'custom-block';
	protected static $invalidFile = 'this-file-doesnt-exit';

	public function testExist() {
		$b = new Block(static::$validFile);
		$this->assertTrue($b->exists());
	}

	public function testDoNotExist() {
		$b = new Block((static::$invalidFile));
		$this->assertFalse($b->exists());
	}

	public function testData() {
		$b = new Block(static::$validFile);
		$b->addData('a', 1);
		$b->addData('b', 2);
		$this->assertEquals($b->getData('a'), 1);
		$this->assertEquals($b->getData('b'), 2);
	}

	public function testDataDoNotExist() {
		$b = new Block((static::$invalidFile));
		$b->addData('a', 1);
		$b->addData('b', 2);
		$this->assertEquals($b->getData('a'), 1);
		$this->assertEquals($b->getData('b'), 2);
	}

	public function testToString() {
		$b = new Block(static::$validFile);
		$this->assertEquals($b->toString(), 'My custom block HTML');
		$this->assertEquals((string) $b, 'My custom block HTML');
	}

	public function testToStringEmpty() {
		$b = new Block(static::$invalidFile);
		$this->assertEquals($b->toString(), '');
	}

}
