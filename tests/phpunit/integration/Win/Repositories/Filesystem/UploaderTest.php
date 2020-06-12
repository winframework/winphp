<?php

namespace Win\Repositories\Filesystem;

use PHPUnit\Framework\TestCase;
use Win\Repositories\Filesystem;

class UploaderTest extends TestCase
{
	public function testConstructor()
	{
		$dir = 'data/dir';
		$fs = new Filesystem();
		$fs->delete($dir);

		new Uploader($dir);
		$this->assertTrue($fs->exists($dir));
	}

	/**
	 * @expectedException \Exception
	 */
	public function testPrepareError()
	{
		$uploader = new Uploader('data/dir');
		$uploader->prepare(['error' => 1]);
	}

	/**
	 * @expectedException \Exception
	 */
	public function testPrepareNull()
	{
		$uploader = new Uploader('data/dir');
		$uploader->prepare(null);
	}

	public function testPrepare()
	{
		$uploader = new Uploader('data/dir');
		$uploader->prepare(['error' => 0]);
	}

	public function testUploadNull()
	{
		$uploader = new Uploader('data/dir');

		$this->assertNull($uploader->upload());
	}

	public function testUpload()
	{
		$dir = 'data/dir';
		$extension = 'md';
		$temp = [
			'error' => 0,
			'tmp_name' => '1m123123daa34n2l',
			'name' => "data/dir/teste.$extension",
		];
		
		$uploader = new Uploader($dir);
		$uploader->prepare($temp);
		$file = $uploader->upload();

		$this->assertContains($dir, $file->getPath());
		$this->assertContains(".$extension", $file->getPath());
	}

	public function testUploadGeneratedName()
	{
		$dir = 'data/dir';
		$extension = 'md';
		$temp = [
			'error' => 0,
			'tmp_name' => '1m123123daa34n2l',
			'name' => "data/dir/teste.$extension",
		];
		$customName = 'FAKE_NAME';

		$uploader = new Uploader($dir);
		$uploader->prepare($temp);
		$file = $uploader->upload($customName);

		$this->assertContains("$dir/$customName.$extension", $file->getPath());
	}
}
