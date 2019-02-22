<?php

namespace Win\Filesystem\Upload;

use PHPUnit\Framework\TestCase;
use Win\Filesystem\Directory;
use Win\Filesystem\File;

/** overwrite function, without validation */
function move_uploaded_file($filename, $destination)
{
	return copy($filename, $destination);
}

class TempFileTest extends TestCase
{
	/** @var TempFile */
	public static $temp;

	/** @var Directory */
	public static $destination;

	const TMP_NAME = 'xA0i90';

	public static function setUpBeforeClass()
	{
		static::$destination = new Directory('data/uploads');
		static::$destination->create();
		static::createTempFile();
	}

	public static function tearDownAfterClass()
	{
		static::$destination->delete();
	}

	public static function createTempFile()
	{
		$temp = TempFile::create(static::TMP_NAME);

		$_FILES = [
			'file' => [
				'tmp_name' => $temp->getAbsolutePath(),
				'name' => 'meu-arquivo.txt',
				'type' => 'txt',
				'size' => $temp->getSize(),
				'error' => 0,
			],
			'empty' => [],
		];

		static::$temp = TempFile::fromFiles('file');
	}

	public function testCreate()
	{
		$temp = TempFile::create('teste');
		$this->assertInstanceOf(TempFile::class, $temp);
		$this->assertTrue($temp->exists());
	}

	public function testFromFiles()
	{
		$this->assertTrue(static::$temp->exists());
		$temp = TempFile::fromFiles('empty');
		$this->assertFalse($temp->exists());
	}

	public function testIsTemporary()
	{
		$this->assertTrue(static::$temp->isTemporary());
		$temp = new TempFile('teste');
		$this->assertFalse($temp->isTemporary());
	}

	public function testGetName()
	{
		$this->assertContains(static::TMP_NAME, static::$temp->getName());
	}

	public function testGetExtension()
	{
		$this->assertEquals('txt', static::$temp->getExtension());
		$temp = new TempFile('teste');
		$this->assertEquals('', $temp->getExtension());
	}

	public function setGetAbsolutePath()
	{
		$path = 'data/files/not-temporary';
		$file = new File($path);
		$file->write('content');
		$this->assertEquals('/tmp/not-temporary', new TempFile($path));
		$this->assertEquals('/tmp/temporary' . TempFile::create('temporary'));
	}

	public function testMove()
	{
		$temp = static::$temp;
		$moved = $temp->move(static::$destination);

		$this->assertTrue($moved);
		$this->assertTrue($temp->exists());
		$this->assertContains('data/uploads', $temp->getAbsolutePath());
		$this->assertEquals(static::$destination->getAbsolutePath(), $temp->getDirectory()->getAbsolutePath());
	}

	public function testMoveWithName()
	{
		static::createTempFile();
		$temp = static::$temp;
		$moved = $temp->move(static::$destination, 'new-name');

		$this->assertTrue($moved);
		$this->assertTrue($temp->exists());
		$this->assertContains('data/uploads/new-name.txt', $temp->getAbsolutePath());
	}
}
