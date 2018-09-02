<?php

namespace Win\File;

class UploaderTest extends \PHPUnit_Framework_TestCase {

	/** @var Uploader */
	public static $uploader;

	public static function setUpBeforeClass() {
		$file = new File('data/tmp/test-upload.md');
		$file->write('content');
		$_FILES = array(
			'file' => array(
				'tmp_name' => $file->getPath(),
				'name' => $file->toString(),
				'type' => $file->getType(),
				'size' => $file->getSize(),
				'error' => 0,
			),
			'empty' => array()
		);
	}

	public function setUp() {
		$file = new File('data/tmp/test-upload.md');
		$file->write('content');

		static::$uploader = new Uploader(new Directory('data/upload'));
	}

	public static function tearDownAfterClass() {
		$tmp = new Directory('data/tmp');
		$tmp->delete();

		$upload = new Directory('data/upload');
		$upload->delete();
	}

	public function testPrepare() {
		$prepared = static::$uploader->prepare('file');
		$this->assertTrue($prepared);
	}

	public function testPrepare_Fail() {
		$prepared = static::$uploader->prepare('not-exist');
		$this->assertFalse($prepared);
	}

	public function testPrepare_Empty() {
		$prepared = static::$uploader->prepare('empty');
		$this->assertFalse($prepared);
	}

	public function testUpload_Exist() {
		static::$uploader->prepare('file');
		$success = static::$uploader->upload();

		$this->assertTrue($success);
		$this->assertInstanceOf(File::class, static::$uploader->getFile());
		$this->assertEquals('data/upload/test-upload.md', static::$uploader->getFile()->getPath());
	}

	public function testUpload_NotExist() {
		static::$uploader->prepare('empty');
		$success = static::$uploader->upload();

		$this->assertFalse($success);
		$this->assertNull(static::$uploader->getFile());
	}

	public function testUpload_Rename() {
		static::$uploader->prepare('file');
		$success = static::$uploader->upload('novo-nome');

		$this->assertTrue($success);
		$this->assertInstanceOf(File::class, static::$uploader->getFile());
		$this->assertEquals('data/upload/novo-nome.md', static::$uploader->getFile()->getPath());
	}

}
