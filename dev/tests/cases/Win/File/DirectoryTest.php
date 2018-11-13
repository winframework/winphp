<?php

namespace Win\File;

use PHPMailer\PHPMailer\Exception;

class DirectoryTest extends \PHPUnit_Framework_TestCase {

	/** @var Directory */
	public static $dirInexistent;

	/** @var Directory */
	public static $dir;

	/** @var Directory */
	public static $sub;

	public static function setUpBeforeClass() {
		static::$dirInexistent = new Directory('my-sample/directory');
		static::$dir = new Directory('data/dir');
		static::$sub = new Directory('data/dir/sub1');
	}

	public static function tearDownAfterClass() {
		$dir = new Directory('data/dir');
		$dir->delete();
	}

	public function testGetPath() {
		$this->assertContains('my-sample/directory', static::$dirInexistent->getAbsolutePath());
		$this->assertNotEquals('my-sample/directory', static::$dirInexistent->getAbsolutePath());
		$this->assertEquals('my-sample/directory', static::$dirInexistent->getPath());
	}

	public function testToString() {
		$this->assertEquals('data/dir', (string) static::$dir);
	}

	public function testGetName() {
		$this->assertEquals('dir', static::$dir->getName());
	}

	public function testGetBaseName() {
		$this->assertEquals('dir', static::$dir->getBaseName());
	}

	public function testValidComplexPath() {
		new Directory('m');
		new Directory('my-sAmple');
		new Directory('1my-sample1');
		new Directory('_my_-sample-');
		new Directory('7_my_-sam3ple-6');
		new Directory('my-_sam.ple_/dir._7.');
		new Directory('_sam.ple_/dir._7');
	}

	/** @expectedException Exception */
	public function testPath_MultiSlash() {
		new Directory('my//sample');
	}

	/** @expectedException Exception */
	public function testPath_EndSlash() {
		new Directory('my-sample/');
	}

	/** @expectedException Exception */
	public function testPath_SpecialChar() {
		new Directory('my-sãmple');
	}

	/** @expectedException Exception */
	public function testPath_Space() {
		new Directory('my sample');
	}

	public function testExits() {
		$dir = new Directory('data');
		$this->assertTrue($dir->exists());
	}

	public function testExits_False() {
		$this->assertFalse(static::$dirInexistent->exists());
	}

	public function testExist_File_False() {
		$dirFile = new Directory('index.php');
		$this->assertFalse($dirFile->exists());
	}

	public function testCreate() {
		static::$dir->delete();
		static::$dir->create();
		$this->assertTrue(static::$dir->exists());
		$this->assertEquals('0755', static::$dir->getChmod());
	}

	public function testCreate_SetPermission() {
		$sub = new Directory('data/dir/sub');
		$sub->create(0611);
		$this->assertEquals('0611', $sub->getChmod());
	}

	/** @expectedException Exception */
	public function testCreate_NoPermission() {
		$dir = new Directory('data/dir/not-permited');
		$dir->create(0611);

		$sub = new Directory('data/dir/not-permited/sub');
		$sub->create();
	}

	public function testRename() {
		$dir = new Directory('data/dir/teste2');
		$dir->create();
		$this->assertTrue($dir->exists());

		$old = clone $dir;
		$dir->rename('teste3');
		$this->assertTrue($dir->exists());
		$this->assertFalse($old->exists());
	}

	public function testGetItemsName_TwoValues() {
		static::$dir->delete();
		static::$dir->create();
		static::$sub->create();
		$sub2 = new Directory('data/dir/sub2');
		$sub2->create();

		$content = static::$dir->getItemsName();
		$this->assertEquals('sub1', $content[0]);
		$this->assertEquals('sub2', $content[1]);
		$this->assertEquals(2, count(static::$dir->getItemsName()));
	}

	public function testGetItems() {
		$file = new File('data/dir/file.txt');
		$file->write('file content');

		$items = static::$dir->getItems();
		$this->assertInstanceOf(Storable::class, $items[0]);
		$this->assertInstanceOf(Storable::class, $items[1]);

		$this->assertInstanceOf(File::class, $items[0]);
		$this->assertInstanceOf(Directory::class, $items[1]);

		$this->assertEquals('file', $items[0]->getName());
		$this->assertEquals('sub1', $items[1]->getName());
	}

	public function testIsEmpty() {
		$this->assertFalse(static::$dir->isEmpty());
		$this->assertTrue(static::$sub->isEmpty());
	}

	public function testDeleteContent() {
		static::$dir->delete();
		static::$dir->create();
		static::$sub->create();
		$this->assertEquals(1, count(static::$dir->getItemsName()));

		static::$dir->clear();
		$this->assertEquals(0, count(static::$dir->getItemsName()));
		$this->assertTrue(static::$dir->exists());
	}

	public function testChmod() {
		static::$dir->setChmod(0744);
		$this->assertEquals('744', static::$dir->getChmod());
		static::$dir->setChmod(0755);
		$this->assertEquals('755', static::$dir->getChmod());
	}

	public function testDelete() {
		static::$sub->create();
		static::$sub->delete();
		$this->assertFalse(static::$sub->exists());
	}

	public function testGetLastModifiedDate() {
		$ts = filemtime(BASE_PATH . '/data/dir');
		$this->assertEquals($ts, static::$dir->getLastModifiedDate()->getTimestamp());
		$this->assertEquals(date('m'), static::$dir->getLastModifiedDate()->format('m'));
	}

}
