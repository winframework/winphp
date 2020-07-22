<?php

namespace Win\Repositories\Filesystem;

use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
	/** @var Image */
	public static $img;

	/** @var Image */
	public static $imgInexistent;

	public function testExist()
	{
		$this->assertTrue(static::$img->exists());
		$this->assertFalse(static::$imgInexistent->exists());
	}

	public function testGetWidth()
	{
		$this->assertEquals(200, static::$img->getWidth());
		$this->assertEquals(null, static::$imgInexistent->getWidth());
	}

	public function testGetHeight()
	{
		$this->assertEquals(166, static::$img->getHeight());
		$this->assertEquals(null, static::$imgInexistent->getHeight());
	}

	public static function setUpBeforeClass(): void
	{
		static::$imgInexistent = new Image('data/images/not-exist.jpg');
		static::$img = new Image('data/images/image.png');
	}
}
