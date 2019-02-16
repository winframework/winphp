<?php

namespace Win\Filesystem\Upload;

use PHPUnit\Framework\TestCase;
use Win\Filesystem\Directory;
use Win\Filesystem\File;

class UploaderTest extends TestCase {

	/** @var Uploader */
	public static $uploader;

	public static function setUpBeforeClass() {
		TempFileTest::createTempFile();
	}

	public static function prepare() {
		return static::$uploader->prepare(TempFile::create('00'));
	}

	public function setUp() {
		$file = new File('data/tmp/test-upload.md');
		$file->write('content');

		static::$uploader = new Uploader(new Directory('data/uploads'));
	}

	public static function tearDownAfterClass() {
		$tmp = new Directory('data/tmp');
		$tmp->delete();

		$upload = new Directory('data/uploads');
		$upload->delete();
	}

	public function testPrepare() {
		$prepared = static::prepare();
		$this->assertTrue($prepared);
	}

	public function testPrepare_NotExist() {
		$prepared = static::$uploader->prepare(TempFile::fromFiles('not-exist'));
		$this->assertFalse($prepared);
	}

	public function testUpload() {
		static::prepare();
		$success = static::$uploader->upload('test-upload');

		$this->assertTrue($success);
		$this->assertEquals('test-upload', static::$uploader->getUploaded()->getName());
	}

}
