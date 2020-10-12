<?php

namespace Win\Services;

use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{
	public function setUp(): void
	{
		$fs = new Filesystem();
		$fs->delete('data/dir');
	}

	public static function tearDownAfterClass(): void
	{
		$fs = new Filesystem();
		$fs->delete('data/dir');
	}

	public function testExists()
	{
		$fs = new Filesystem();
		$this->assertTrue($fs->exists('data/teste.md'));
	}

	public function testCreate()
	{
		$fs = new Filesystem();
		$dirPath = 'data/dir/sub1';
		$fs->create($dirPath);

		$this->assertTrue($fs->exists($dirPath));
	}

	/** @expectedException Exception */
	public function testCreateError()
	{
		$fs = new Filesystem();
		$dirPath = 'data/dir/not-autorized';
		$fs->create($dirPath, 0555);
		$fs->create($dirPath . '/inside');
	}

	public function testDelete()
	{
		$fs = new Filesystem();
		$dirPath = 'data/dir/sub1';
		$fs->create($dirPath);
		$fs->delete($dirPath);

		$this->assertFalse($fs->exists($dirPath));
	}

	public function testChildren()
	{
		$dir = 'data/dir';
		$fs = new Filesystem();

		$fs->create($dir . '/sub1');
		$fs->create($dir . '/sub2');

		$this->assertEquals(2, count($fs->children($dir)));
	}

	public function testCount()
	{
		$dir = 'data/dir';
		$fs = new Filesystem();

		$fs->create($dir . '/sub1');

		$this->assertEquals(1, $fs->count($dir));
	}

	public function testRename()
	{
		$old = 'data/dir/old';
		$new = 'data/dir/new';
		$fs = new Filesystem();

		$fs->create($old);
		$fs->rename($old, $new);

		$this->assertTrue($fs->exists($new));
		$this->assertFalse($fs->exists($old));
	}

	public function testMove()
	{
		$dir1 = 'data/dir/dir1';
		$dir2 = 'data/dir/dir2';
		$moved = 'moved';
		$fs = new Filesystem();

		$fs->create("$dir1/$moved");
		$fs->create($dir2);
		$fs->move($dir1, $dir2);

		$this->assertTrue($fs->exists("$dir2/$moved"));
		$this->assertFalse($fs->exists("$dir1/$moved"));
	}

	public function testRead()
	{
		$file = 'data/teste.md';
		$fileNotExist = 'data/not-exit.md';
		$fs = new Filesystem();

		$this->assertEquals('content', $fs->read($file));
		$this->assertEquals(null, $fs->read($fileNotExist));
	}

	public function testWrite()
	{
		$file = 'data/dir/new.md';
		$content = 'My content';
		$content2 = 'My content 2';
		$fs = new Filesystem();

		$fs->write($file, $content);
		$this->assertEquals($content, $fs->read($file));

		$fs->write($file, $content2);
		$this->assertEquals($content2, $fs->read($file));
	}

	/**
	 * @expectedException \Exception
	 */
	public function testReceiveFileError()
	{
		$fs = new Filesystem();
		$fs->receiveFile(['error' => 1]);
	}

	/**
	 * @expectedException \Exception
	 */
	public function testReceiveFileNull()
	{
		$fs = new Filesystem();
		$fs->receiveFile(null);
	}

	public function testReceiveFile()
	{
		$fs = new Filesystem();
		$fs->receiveFile(['error' => 0]);
	}

	/**
	 * @expectedException \Exception
	 */
	public function testReceiveFileExtension()
	{
		$extension = 'md';
		$temp = [
			'error' => 0,
			'tmp_name' => '1m123123daa34n2l',
			'name' => "data/dir/teste.$extension",
		];

		$fs = new Filesystem();
		$fs->receiveFile($temp, ['mp3', 'mp4']);
	}

	/**
	 * @expectedException \Exception
	 */
	public function testUploadError()
	{
		$fs = new Filesystem();

		$fs->upload('data/dir');
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

		$fs = new Filesystem();
		$fs->receiveFile($temp);
		$file = $fs->upload($dir);

		$this->assertStringContainsString(".$extension", $file);
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

		$fs = new Filesystem();
		$fs->receiveFile($temp);
		$file = $fs->upload($dir, $customName);

		$this->assertStringContainsString("$customName.$extension", $file);
	}
}