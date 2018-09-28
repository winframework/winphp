<?php

namespace Win\File\Upload;

use PHPUnit_Framework_TestCase;
use Win\File\Directory;
use Win\File\File;

class UploaderTest extends PHPUnit_Framework_TestCase {

	/** @var Uploader */
	public static $uploader;

	public static function setUpBeforeClass() {
		$file = tmpfile();
		$path = stream_get_meta_data($file)['uri'];

		$_FILES = array(
			'file' => array(
				'tmp_name' => $path,
				'name' => 'meu-arquivo.txt',
				'type' => 'txt',
				'size' => 10,
				'error' => 0,
			),
			'empty' => array()
		);
	}

	public static function prepare() {
		return static::$uploader->prepare(TempFile::fromFiles('file'));
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
		$prepared = static::prepare();
		//$this->assertTrue($prepared);
	}

	public function testPrepare_Fail() {
		$prepared = static::$uploader->prepare(TempFile::fromFiles('not-extist'));
		//$this->assertFalse($prepared);
	}

	public function testPrepare_Empty() {
		$prepared = static::$uploader->prepare(TempFile::fromFiles('empty'));
		//$this->assertFalse($prepared);
	}

	public function testUpload_NotExist() {
		static::prepare();
		$success = static::$uploader->upload();

		//$this->assertFalse($success);
		//$this->assertNull(static::$uploader->getFile());
	}

	public function testUpload() {
		static::prepare();
		$success = static::$uploader->upload();

		//$this->assertTrue($success);
		//$this->assertInstanceOf(File::class, static::$uploader->getFile());
		//$this->assertEquals('data/upload/test-upload.md', static::$uploader->getFile()->getPath());
	}

	public function testUpload_Rename() {
		static::prepare();
		$success = static::$uploader->upload('novo-nome');

		//$this->assertTrue($success);
		//$this->assertInstanceOf(File::class, static::$uploader->getFile());
		//$this->assertEquals('data/upload/novo-nome.md', static::$uploader->getFile()->getPath());
	}

	public function testUpload_GenerateName() {
		static::prepare();
		//$success = static::$uploader->genarateName()->upload();

		//$this->assertTrue($success);
		//$this->assertInstanceOf(File::class, static::$uploader->getFile());
		//$this->assertNotEquals('data/upload/test-upload.md', static::$uploader->getFile()->getPath());
	}

}
