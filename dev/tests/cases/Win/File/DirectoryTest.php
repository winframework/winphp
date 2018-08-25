<?php

namespace Win\File;

use PHPMailer\PHPMailer\Exception;

class DirectoryTest extends \PHPUnit_Framework_TestCase {

	public function testGetPath() {
		$dir = new Directory('my-sample-directory');
		$this->assertContains('my-sample-directory', $dir->getPath());
		$this->assertNotEquals('my-sample-directory', $dir->getPath());
		$this->assertEquals('my-sample-directory', $dir->getRelativePath());
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

		$sub = new Directory('data/dir/sub');
		$sub->create(0611);
		$this->assertEquals('0611', $sub->getPermission());
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

	public function testScan() {
		$dir = new Directory('data/dir');
		$content = $dir->scan();
		$this->assertTrue(count($content) == 2);
	}

	public function testChmod() {
		$dir = new Directory('data/dir');
		$dir->chmod(0744);
		$this->assertEquals('744', $dir->getPermission());
		$dir->chmod(0755);
		$this->assertEquals('755', $dir->getPermission());
	}

	public function testDelete() {
		$dir = new Directory('data/dir');
		$dir->delete();
		$this->assertFalse($dir->exists());
	}

}
