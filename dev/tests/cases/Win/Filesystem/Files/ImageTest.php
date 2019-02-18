<?php

namespace Win\Filesystem\Files;

class ImageTest extends \PHPUnit\Framework\TestCase {

	/** @var Image */
	public static $img;

	/** @var Image */
	public static $imgInexistent;

	public function testExist() {
		$this->assertTrue(static::$img->exists());
	}

	public function testNotExist() {
		$this->assertFalse(static::$imgInexistent->exists());
	}

	public function testGetWidth() {
		$this->assertEquals(200, static::$img->getWidth());
	}

	public function testGetWith_Null() {
		$this->assertEquals(null, static::$imgInexistent->getWidth());
	}

	public function testGetHeight() {
		$this->assertEquals(166, static::$img->getHeight());
	}

	public function testGetHeight_Null() {
		$this->assertEquals(null, static::$imgInexistent->getHeight());
	}

	public static function setUpBeforeClass() {
		static::$imgInexistent = new Image('data/images/not-exist.jpg');
		static::$img = new Image('data/images/image.png');
	}

}
