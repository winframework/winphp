<?php

namespace Win\Filesystem\Upload;

use PHPUnit\Framework\TestCase;
use Win\Filesystem\Directory;
use Win\Filesystem\File;

/** overwrite function, without validation */
function move_uploaded_file($filename, $destination) {
	return copy($filename, $destination);
}

class TempFileTest extends TestCase {

	/** @var TempFile */
	public static $temp;

	/** @var Directory */
	public static $destination;

	const TMP_NAME = 'xA0i90';

	public static function setUpBeforeClass() {
		static::$destination = new Directory('data/uploads');
		static::$destination->create();
		static::createTempFile();
	}

	public static function tearDownAfterClass() {
		static::$destination->delete();
	}

	public static function createTempFile() {
		$temp = TempFile::create(static::TMP_NAME);

		$_FILES = array(
			'file' => array(
				'tmp_name' => $temp->getAbsolutePath(),
				'name' => 'meu-arquivo.txt',
				'type' => 'txt',
				'size' => $temp->getSize(),
				'error' => 0,
			),
			'empty' => array()
		);

		static::$temp = TempFile::fromFiles('file');
	}

	public function testCreate() {
		$temp = TempFile::create('teste');
		$this->assertInstanceOf(TempFile::class, $temp);
		$this->assertTrue($temp->exists());
	}

	public function testFromFiles() {
		$this->assertTrue(static::$temp->exists());
	}

	public function testFromFiles_Empty() {
		$temp = TempFile::fromFiles('empty');
		$this->assertFalse($temp->exists());
	}

	public function testIsTemporary() {
		$this->assertTrue(static::$temp->isTemporary());
	}

	public function testIsNotTemporary() {
		$temp = new TempFile('teste');
		$this->assertFalse($temp->isTemporary());
	}

	public function testGetName() {
		$this->assertContains(static::TMP_NAME, static::$temp->getName());
	}

	public function testGetExtension() {
		$this->assertEquals('txt', static::$temp->getExtension());
	}

	public function testGetExtension_Empty() {
		$temp = new TempFile('teste');
		$this->assertEquals('', $temp->getExtension());
	}

	public function setGetAbsolutePath() {
		$this->assertEquals('/tmp/temporary' . TempFile::create('temporary'));
	}

	public function setGetAbsolutePath_NotTemporary() {
		$file = new File('data/files/not-temporary');
		$file->write('content');
		$this->assertEquals('/tmp/not-temporary', new TempFile('data/files/not-temporary'));
	}

	public function testMove() {
		$temp = static::$temp;
		$moved = $temp->move(static::$destination);

		$this->assertTrue($moved);
		$this->assertTrue($temp->exists());
		$this->assertContains('data/uploads', $temp->getAbsolutePath());
		$this->assertEquals(static::$destination->getAbsolutePath(), $temp->getDirectory()->getAbsolutePath());
	}

	public function testMove_WithName() {
		static::createTempFile();
		$temp = static::$temp;
		$moved = $temp->move(static::$destination, 'new-name');

		$this->assertTrue($moved);
		$this->assertTrue($temp->exists());
		$this->assertContains('data/uploads/new-name.txt', $temp->getAbsolutePath());
	}

}