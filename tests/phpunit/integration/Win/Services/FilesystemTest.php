<?php

namespace Win\Services;

use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{
	public function setUp(): void
	{
		$fs = Filesystem::instance();
		$fs->delete('data/dir');
	}

	public static function tearDownAfterClass(): void
	{
		$fs = Filesystem::instance();
		$fs->delete('data/dir');
	}

	public function testExists()
	{
		$fs = Filesystem::instance();
		$this->assertTrue($fs->exists('data/teste.md'));
	}

	public function testCreate()
	{
		$fs = Filesystem::instance();
		$dirPath = 'data/dir/sub1';
		$fs->create($dirPath);

		$this->assertTrue($fs->exists($dirPath));
	}

	/** @expectedException Exception */
	public function testCreateError()
	{
		$fs = Filesystem::instance();
		$dirPath = 'data/dir/not-autorized';
		$fs->create($dirPath, 0555);
		$fs->create($dirPath . '/inside');
	}

	public function testDelete()
	{
		$fs = Filesystem::instance();
		$dirPath = 'data/dir/sub1';
		$fs->create($dirPath);
		$fs->delete($dirPath);

		$this->assertFalse($fs->exists($dirPath));
	}

	public function testChildren()
	{
		$dir = 'data/dir';
		$fs = Filesystem::instance();

		$fs->create($dir . '/sub1');
		$fs->create($dir . '/sub2');

		$this->assertEquals(2, count($fs->children($dir)));
	}

	public function testCount()
	{
		$dir = 'data/dir';
		$fs = Filesystem::instance();

		$fs->create($dir . '/sub1');

		$this->assertEquals(1, $fs->count($dir));
	}

	public function testRename()
	{
		$old = 'data/dir/old';
		$new = 'data/dir/new';
		$fs = Filesystem::instance();

		$fs->create($old);
		$fs->rename($old, $new);

		$this->assertTrue($fs->exists($new));
		$this->assertFalse($fs->exists($old));
	}

	/**
	 * @expectedException \Exception
	 */
	public function testRenameError()
	{
		$old = 'data/dir/NOT-EXISTENT';
		$new = 'data/dir/new';
		$fs = Filesystem::instance();

		$fs->rename($old, $new);
	}

	public function testMove()
	{
		$dir1 = 'data/dir/dir1';
		$dir2 = 'data/dir/dir2';
		$moved = 'moved';
		$fs = Filesystem::instance();

		$fs->create("$dir1/$moved");
		$fs->create($dir2);
		$fs->move($dir1, $dir2);

		$this->assertTrue($fs->exists("$dir2/$moved"));
		$this->assertFalse($fs->exists("$dir1/$moved"));
	}

	/**
	 * @expectedException \Exception
	 */
	public function testRead()
	{
		$file = 'data/teste.md';
		$fileNotExist = 'data/not-exit.md';
		$fs = Filesystem::instance();

		$this->assertEquals('content', $fs->read($file));
		$this->assertEquals(null, $fs->read($fileNotExist));
	}

	public function testWrite()
	{
		$file = 'data/dir/new.md';
		$content = 'My content';
		$content2 = 'My content 2';
		$fs = Filesystem::instance();

		$fs->write($file, $content);
		$this->assertEquals($content, $fs->read($file));

		$fs->write($file, $content2);
		$this->assertEquals($content2, $fs->read($file));
	}

	/**
	 * @expectedException \Exception
	 */
	public function testWriteError()
	{
		$file = 'data/not-allowed/example.md';
		$content = 'My content';
		$fs = Filesystem::instance();
		$fs->create('data/not-allowed', 0444);

		$fs->write($file, $content);
	}

	/**
	 * @expectedException \Exception
	 */
	public function testReceiveFileError()
	{
		$fs = Filesystem::instance();
		$fs->receiveFile(['error' => 1]);
	}

	/**
	 * @expectedException \Exception
	 */
	public function testReceiveFileNull()
	{
		$fs = Filesystem::instance();
		$fs->receiveFile(null);
	}

	public function testReceiveFile()
	{
		$fs = Filesystem::instance();
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

		$fs = Filesystem::instance();
		$fs->receiveFile($temp, ['mp3', 'mp4']);
	}

	/**
	 * @expectedException \Exception
	 */
	public function testUploadError()
	{
		$fs = Filesystem::instance();

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

		$fs = Filesystem::instance();
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

		$fs = Filesystem::instance();
		$fs->receiveFile($temp);
		$file = $fs->upload($dir, $customName);

		$this->assertStringContainsString("$customName.$extension", $file);
	}
}
