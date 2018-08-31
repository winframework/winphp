<?php

namespace Win\File;

class ImageTest extends \PHPUnit_Framework_TestCase {

	/** @var Image */
	public static $existent;

	/** @var Image */
	public static $inexistent;

	public function testExist() {
		$this->assertTrue(static::$existent->exists());
		$this->assertFalse(static::$inexistent->exists());
	}

	public function testGetWidth() {
		$this->assertEquals(200, static::$existent->getWidth());
		$this->assertEquals(null, static::$inexistent->getWidth());
	}

	public function testGetHeight() {
		$this->assertEquals(166, static::$existent->getHeight());
		$this->assertEquals(null, static::$inexistent->getHeight());
	}

	public static function setUpBeforeClass() {
		static::$inexistent = new Image('data/image/not-exist.jpg');
		static::$existent = new Image('data/image/image.png');
	}

}
