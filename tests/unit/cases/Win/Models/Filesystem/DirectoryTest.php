<?php

namespace Win\Models\Filesystem;

use PHPUnit\Framework\TestCase;
use Win\Repositories\Filesystem;

class DirectoryTest extends TestCase
{
	/** @var Directory */
	public static $dirInexistent;

	/** @var Directory */
	public static $dir;

	/** @var Directory */
	public static $sub;

	public static function setUpBeforeClass()
	{
		$fs = new Filesystem();
		$fs->delete('data/dir');
		$fs->create('data/dir');
		$fs->create('data/dir/sub1');
		$fs->write('data/dir/file.txt','teste');

		static::$dirInexistent = new Directory('my-sample/directory');
		static::$dir = new Directory('data/dir');
		static::$sub = new Directory('data/dir/sub1');
	}

	public function testGetPath()
	{
		$dir = 'my-sample/directory';
		$this->assertContains($dir, static::$dirInexistent->getAbsolutePath());
		$this->assertNotEquals($dir, static::$dirInexistent->getAbsolutePath());
		$this->assertEquals($dir, static::$dirInexistent->getPath());
	}

	public function testToString()
	{
		$this->assertEquals('data/dir', (string) static::$dir);
	}

	public function testGetName()
	{
		$this->assertEquals('dir', static::$dir->getName());
	}

	public function testGetBaseName()
	{
		$this->assertEquals('dir', static::$dir->getBaseName());
	}

	public function testValidComplexPath()
	{
		new Directory('m');
		new Directory('my-sAmple');
		new Directory('1my-sample1');
		new Directory('_my_-sample-');
		new Directory('7_my_-sam3ple-6');
		new Directory('my-_sam.ple_/dir._7.');
		new Directory('_sam.ple_/dir._7');
	}

	/** @expectedException Exception */
	public function testPathWithMultiSlash()
	{
		new Directory('my//sample');
	}

	/** @expectedException Exception */
	public function testPathWithEndSlash()
	{
		new Directory('my-sample/');
	}

	/** @expectedException Exception */
	public function testPathWithSpecialChar()
	{
		new Directory('my-sãmple');
	}

	/** @expectedException Exception */
	public function testPathWithSpace()
	{
		new Directory('my sample');
	}

	public function testExist()
	{
		$dir = new Directory('data');
		$dirFile = new Directory('index.php');
		$this->assertTrue($dir->exists());
		$this->assertFalse($dirFile->exists());
		$this->assertFalse(static::$dirInexistent->exists());
	}

	public function testIsEmpty()
	{
		$this->assertFalse(static::$dir->isEmpty());
		$this->assertTrue(static::$sub->isEmpty());
	}

	public function testGetLastModifiedDate()
	{
		$ts = filemtime(BASE_PATH . '/data/dir');
		$modifiedAt = static::$dir->getLastModifiedDate();
		$this->assertEquals($ts, $modifiedAt->getTimestamp());
		$this->assertEquals(date('m'), $modifiedAt->format('m'));
	}

	public function testGetChildren()
	{
		$children = static::$dir->getChildren();
		$this->assertEquals(2, count($children));
		$this->assertEquals('file.txt', $children[0]->getBaseName());
		$this->assertEquals('sub1', $children[1]->getBaseName());
	}
}
