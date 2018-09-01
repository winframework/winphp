<?php

namespace Win\File;

class FileTest extends \PHPUnit_Framework_TestCase {

	/** @var File */
	private $file;

	/** @expectedException \Exception */
	public function testPath_Empty() {
		$this->file = new File('');
	}

	/** @expectedException \Exception */
	public function testPath_SpecialChar() {
		$this->file = new File('inva$lidâ');
	}

	/** @expectedException \Exception */
	public function testPath_Space() {
		$this->file = new File('invalid name');
	}

	/** @expectedException \Exception */
	public function testPath_DirectoryInvalid() {
		$this->file = new File('inva$lidâ/my-file');
	}

	public function testPath_RemoveEndSlash() {
		$this->file = new File('valid-name/');
		$this->assertEquals('', $this->file->getExtension());
		$this->assertEquals('valid-name', $this->file->getName());
		$this->assertEquals('valid-name', $this->file->toString());
	}

	public function testPath_Number() {
		$this->file = new File('7valid10name3');
	}

	public function testPath_UnderscoreHiphen() {
		$this->file = new File('_valid-name-');
	}

	public function testPath_Slash() {
		$this->file = new File('valid-name/d');
	}

	public function testPath_Dot() {
		$this->file = new File('.my-file.teste.');
	}

	public function testPath_WithExtension() {
		$this->file = new File('7valid_-10name3.jpg');
	}

	public function testPath_FullPath() {
		$this->file = new File('my-directory/sub-directory/7valid_-10name3.jpg');
	}

	public function testGetName() {
		$this->initExistentFile();
		$this->assertEquals('exist', $this->file->getName());
	}

	public function testGetExtension() {
		$this->initExistentFile();
		$this->assertEquals('html', $this->file->getExtension());
	}

	public function testGetExtension_Empty() {
		$this->file = new File('my-file/without/extension');
		$this->assertEquals('', $this->file->getExtension());
	}

	public function testGetPath() {
		$this->initExistentFile();
		$this->assertContains('data/file/exist.html', $this->file->getAbsolutePath());
		$this->assertNotEquals('data/file/exist.html', $this->file->getAbsolutePath());
		$this->assertEquals('data/file/exist.html', $this->file->getPath());
	}

	public function testToString() {
		$this->initExistentFile();
		$this->assertEquals('exist.html', $this->file->__toString());
		$this->assertEquals('exist.html', $this->file->toString());
	}

	public function testGetDirectory() {
		$this->initExistentFile();
		$this->assertEquals('file', $this->file->getDirectory()->getName());
	}

	public function testGetDirectory_NotExist() {
		$this->initExistentFile();
		$this->assertEquals('file', $this->file->getDirectory()->getName());
	}

	public function testGetSize() {
		$this->initExistentFile();
		$this->assertTrue($this->file->getSize() > 1);
	}

	public function testGetSize_Empty() {
		$this->initEmptyFile();
		$this->assertEquals(0, $this->file->getSize());
	}

	public function testGetSize_NotExist() {
		$this->file = new File('data/file/doesnt-exist.html');
		$this->assertFalse($this->file->exists());
		$this->assertEquals(false, $this->file->getSize());
	}

	public function testExists() {
		$this->initExistentFile();
		$this->assertTrue($this->file->exists());
	}

	public function testNotExist() {
		$this->initInexistentFile();
		$this->assertFalse($this->file->exists());
	}

	public function testRead_NotExist() {
		$this->initInexistentFile();
		$this->assertFalse($this->file->read());
	}

	public function testRead_Empty() {
		$this->initEmptyFile();
		$this->assertTrue($this->file->exists());
		$this->assertNotFalse($this->file->read());
		$this->assertEquals('', $this->file->read());
	}

	public function testRead_Exist() {
		$this->initExistentFile();
		$this->assertTrue($this->file->exists());
		$this->assertNotFalse($this->file->read());
		$this->assertContains('content', $this->file->read());
	}

	public function testWrite_NotExist() {
		$this->initInexistentFile();
		$this->assertTrue($this->file->write('My first content\n'));
		$this->assertContains('My first content', $this->file->read());
		$this->file->delete();
	}

	public function testWrite_Exist() {
		$this->initExistentFile();
		$this->assertTrue($this->file->write('My second content\n'));
		$this->assertContains('My second content', $this->file->read());
	}

	public function testDelete() {
		$this->initInexistentFile();
		$this->assertTrue($this->file->write('content'));
		$this->assertTrue($this->file->exists());
		$this->file->delete();
		$this->assertFalse($this->file->exists());
		$this->assertFalse($this->file->getSize());
	}

	public function testMove() {
		$this->file = new File('data/file/to-be-moved.txt');
		$this->file->write('my content');

		$this->file->move(new Directory('data'));
		$this->assertEquals('data', $this->file->getDirectory()->getPath());
		$this->assertTrue($this->file->exists());
		$this->file->delete();
	}

	public function testMove_DirectoryNotExist() {
		$this->file = new File('data/file/to-be-moved2.txt');
		$this->file->write('my content');

		$inexist = new Directory('data/inexist');
		$this->file->move($inexist);
		$this->assertEquals('data/inexist', $this->file->getDirectory()->getPath());
		$this->assertTrue($this->file->exists());
		$this->file->delete();
		$inexist->delete();
	}

	public function testRename() {
		$this->file = new File('data/file/to-be-rename.txt');
		$this->file->write('I will receive a new name');
		$this->file->rename('renamed-file');

		$this->assertEquals('data/file', $this->file->getDirectory()->getPath());
		$this->assertTrue($this->file->exists());
		$this->file->delete();
	}

	/** @expectedException Exception */
	public function testRename_SpecialChar() {
		$this->file = new File('data/file/to-be-rename.txt');
		$this->file->rename('renamed-special*-file');
	}

	/** @expectedException Exception */
	public function testRename_Slash() {
		$this->file = new File('data/file/to-be-rename.txt');
		$this->file->rename('invalid/name');
	}

	public function testStrToFilePath() {
		$this->assertEquals('produtos-de-otima-qualidade-2', File::strToValidName('.Produtos-de_óti?ma q.ualida@"de/2-'));
	}

	private function initExistentFile() {
		$this->file = new File('data/file/exist.html');
	}

	private function initInexistentFile() {
		$this->file = new File('data/file/not-exist.html');
	}

	private function initEmptyFile() {
		$this->file = new File('data/file/empty.html');
	}

}
