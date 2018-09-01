<?php

namespace Win\File;

use PHPMailer\PHPMailer\Exception;

class DirectoryTest extends \PHPUnit_Framework_TestCase {

	public function testGetPath() {
		$dir = new Directory('my-sample/directory');
		$this->assertContains('my-sample/directory', $dir->getAbsolutePath());
		$this->assertNotEquals('my-sample/directory', $dir->getAbsolutePath());
		$this->assertEquals('my-sample/directory', $dir->getPath());
	}

	public function testToString() {
		$dir = new Directory('my-string/directory');
		$this->assertEquals('directory', $dir->toString());
		$this->assertEquals('directory', $dir->__toString());
	}

	public function testGetName() {
		$dir = new Directory('my/string/directory');
		$this->assertEquals('directory', $dir->getName());
	}

	public function testValidComplexPath() {
		new Directory('m');
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

	/** @expectedException Exception */
	public function testPath_Uppercase() {
		new Directory('my-sAmple');
	}

	public function testDoNotExits() {
		$dir = new Directory('inexist');
		$this->assertFalse($dir->exists());

		$dirFile = new Directory('index.php');
		$this->assertFalse($dirFile->exists());
	}

	public function testCreate() {
		$dir = new Directory('data/dir');
		$dir->delete();
		$dir->create();
		$this->assertTrue($dir->exists());
		$this->assertEquals('0755', $dir->getPermission());
	}

	public function testCreate_SetPermission() {
		$sub = new Directory('data/dir/sub');
		$sub->create(0611);
		$this->assertEquals('0611', $sub->getPermission());
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
		$dir->rename('data/dir/teste3');
		$this->assertTrue($dir->exists());
		$this->assertFalse($old->exists());
	}

	public function testScan_TwoContents() {
		$dir = new Directory('data/dir');
		$dir->delete();
		$dir->create();
		$sub1 = new Directory('data/dir/sub1');
		$sub1->create();
		$sub2 = new Directory('data/dir/sub2');
		$sub2->create();

		$content = $dir->getItemsName();
		$this->assertEquals('sub1', $content[0]);
		$this->assertEquals('sub2', $content[1]);
		$this->assertEquals(2, count($dir->getItemsName()));
	}
	public function testIsEmpty() {
		$dir = new Directory('data/dir');
		$sub1 = new Directory('data/dir/sub1');
		
		$this->assertFalse($dir->isEmpty());
		$this->assertTrue($sub1->isEmpty());
	}
	public function testDeleteContent() {
		$dir = new Directory('data/dir');
		$dir->delete();
		$dir->create();
		$sub1 = new Directory('data/dir/sub1');
		$sub1->create();
		$this->assertEquals(1, count($dir->getItemsName()));

		$dir->clear();
		$this->assertEquals(0, count($dir->getItemsName()));
		$this->assertTrue($dir->exists());
	}

	public function testChmod() {
		$dir = new Directory('data/dir');
		$dir->chmod(0744);
		$this->assertEquals('744', $dir->getPermission());
		$dir->chmod(0755);
		$this->assertEquals('755', $dir->getPermission());
	}

	public function testDelete() {
		$dir = new Directory('data/dir/sub');
		$dir->create();
		$dir->delete();
		$this->assertFalse($dir->exists());
	}

	public function testStrToFilePath() {
		$this->assertEquals('sample-dir-7', Directory::strToDirectoryName('_Sam.plE_/diR._7/'));
	}

	public static function tearDownAfterClass() {
		$dir = new Directory('data/dir');
		$dir->delete();
	}

}
