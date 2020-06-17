<?php

namespace Win\Repositories\Filesystem;

use PHPUnit\Framework\TestCase;
use Win\Repositories\Filesystem;

class UploaderTest extends TestCase
{
	private Filesystem $fs;

	public function setUp()
	{
		$dir = 'data/dir';
		$this->fs = new Filesystem();
		$this->fs->delete($dir);
	}


	/**
	 * @expectedException \Exception
	 */
	public function testPrepareError()
	{
		$uploader = new Uploader($this->fs);
		$uploader->prepare(['error' => 1]);
	}

	/**
	 * @expectedException \Exception
	 */
	public function testPrepareNull()
	{
		$uploader = new Uploader($this->fs);
		$uploader->prepare(null);
	}

	public function testPrepare()
	{
		$uploader = new Uploader($this->fs);
		$uploader->prepare(['error' => 0]);
	}

	public function testUploadNull()
	{
		$uploader = new Uploader($this->fs);

		$this->assertNull($uploader->upload('data/dir'));
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

		$uploader = new Uploader($this->fs);
		$uploader->prepare($temp);
		$file = $uploader->upload($dir);

		$this->assertStringContainsString($dir, $file->getPath());
		$this->assertStringContainsString(".$extension", $file->getPath());
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

		$uploader = new Uploader($this->fs);
		$uploader->prepare($temp);
		$file = $uploader->upload($dir, $customName);

		$this->assertStringContainsString("$dir/$customName.$extension", $file->getPath());
	}
}
