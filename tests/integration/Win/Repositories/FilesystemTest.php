<?php

namespace Win\Repositories;

use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{
	public function setUp()
	{
		$fs = new Filesystem();
		$fs->delete('data/dir');
	}

	public static function tearDownAfterClass()
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
}
