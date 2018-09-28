<?php

namespace Win\File\Upload;

use PHPUnit_Framework_TestCase;
use Win\File\Directory;

class TempFileTest extends PHPUnit_Framework_TestCase {

	/** @var TempFile */
	public static $temp;

	public static function setUpBeforeClass() {
		static::$temp = TempFile::create('xA0i90000000');
		$_FILES = array(
			'file' => array(
				'tmp_name' => static::$temp->getAbsolutePath(),
				'name' => 'meu-arquivo.txt',
				'type' => 'txt',
				'size' => static::$temp->getSize(),
				'error' => 0,
			),
			'empty' => array()
		);
	}

	public function testCreate() {
		$file = TempFile::create('xA0i90000000');
		$this->assertInstanceOf(TempFile::class, $file);
		$this->assertTrue($file->exists());
	}

	public function testCreate_Content() {
		$file = TempFile::create('xA0i90000000', 'My file content');
		$this->assertEquals('My file content', $file->read());
	}

	public function testGetName() {
		$this->assertContains('xA0i90000000', static::$temp->getName());
	}

	public function testGetExtension() {
		$tmp = new TempFile('xA0i90000000.txt');
		$this->assertEquals('', static::$temp->getExtension());
		$this->assertEquals('', $tmp->getExtension());
	}

	public function setGetAbsolutePath() {
		$this->assertContains('/tmp/xA0i90000000', static::$temp->getAbsolutePath());
	}

	public function testSetName() {
		static::$temp->setName('new-name');
		$this->assertEquals('new-name', static::$temp->getName());
	}

	public function testFromFiles() {
		$tmp = TempFile::fromFiles('file');
		$this->assertTrue($tmp->exists());
		$this->assertEquals(static::$temp->getAbsolutePath(), $tmp->getAbsolutePath());
	}

	public function testMove() {
		$tmp = TempFile::fromFiles('file');
		$destination = new Directory('data/upload');
		$destination->create();
		$moved = $tmp->move($destination);
		$this->assertTrue($moved || !is_uploaded_file($tmp->getAbsolutePath()));
	}

}
