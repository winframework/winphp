<?php

namespace Win\Filesystem;

use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
	/** @var File */
	private $file;

	/** @expectedException \Exception */
	public function testNameEmpty()
	{
		$this->file = new File('');
	}

	/** @expectedException \Exception */
	public function testNameWithSpecialChar()
	{
		$this->file = new File('inva$lidâ');
	}

	/** @expectedException \Exception */
	public function testNameWithSpace()
	{
		$this->file = new File('invalid name');
	}

	/** @expectedException \Exception */
	public function testDirectoryInvalid()
	{
		$this->file = new File('inva$lidâ/my-file');
	}

	public function testDirectoryValid()
	{
		$this->file = new File('valid-name/d');
	}

	public function testNameWithSlash()
	{
		$this->file = new File('valid-name/');
		$this->assertEquals('', $this->file->getExtension());
		$this->assertEquals('valid-name', $this->file->getName());
	}

	public function testNameWithNumber()
	{
		$this->file = new File('7valid10name3');
	}

	public function testNameWithUnderscoreHiphen()
	{
		$this->file = new File('_valid-name-');
	}

	public function testNameWithDot()
	{
		$this->file = new File('.my-file.teste.');
	}

	public function testNameWithExtension()
	{
		$this->file = new File('7valid_-10name3.jpg');
	}

	public function testNameFullPath()
	{
		$this->file = new File('my-directory/sub-directory/7valid_-10name3.jpg');
	}

	public function testGetName()
	{
		$this->initExistFile();
		$this->assertEquals('exist', $this->file->getName());
	}

	public function testGetBaseName()
	{
		$this->initExistFile();
		$this->assertEquals('exist.html', $this->file->getBaseName());
	}

	public function testGetExtension()
	{
		$this->initExistFile();
		$this->assertEquals('html', $this->file->getExtension());
		$this->file = new File('my-file/without/extension');
		$this->assertEquals('', $this->file->getExtension());
	}

	public function testGetType()
	{
		$this->initExistFile();
		$this->assertEquals('text/plain', $this->file->getType());
	}

	public function testGetPath()
	{
		$this->initExistFile();
		$path = 'data/files/exist.html';
		$this->assertContains($path, $this->file->getAbsolutePath());
		$this->assertNotEquals($path, $this->file->getAbsolutePath());
		$this->assertEquals($path, $this->file->getPath());
	}

	public function testToString()
	{
		$this->initExistFile();
		$this->assertEquals('data/files/exist.html', (string) $this->file);
	}

	public function testGetDirectory()
	{
		$this->initExistFile();
		$this->assertEquals('files', $this->file->getDirectory()->getName());
	}

	public function testGetSize()
	{
		$this->initExistFile();
		$this->assertTrue($this->file->getSize() > 1);
		$this->initEmptyFile();
		$this->assertEquals(0, $this->file->getSize());
		$this->file = new File('data/files/doesnt-exist.html');
		$this->assertEquals(false, $this->file->getSize());
	}

	public function testExists()
	{
		$this->initExistFile();
		$this->assertTrue($this->file->exists());
		$this->initDontExistFile();
		$this->assertFalse($this->file->exists());
	}

	public function testReadNotExist()
	{
		$this->initDontExistFile();
		$this->assertFalse($this->file->read());
	}

	public function testReadEmpty()
	{
		$this->initEmptyFile();
		$this->assertTrue($this->file->exists());
		$this->assertNotFalse($this->file->read());
		$this->assertEquals('', $this->file->read());
	}

	public function testReadExist()
	{
		$this->initExistFile();
		$this->assertTrue($this->file->exists());
		$this->assertNotFalse($this->file->read());
		$this->assertContains('content', $this->file->read());
	}

	public function testWriteNotExist()
	{
		$this->initDontExistFile();
		$this->assertTrue($this->file->write('My first content\n'));
		$this->assertContains('My first content', $this->file->read());
		$this->file->delete();
	}

	public function testWriteExist()
	{
		$this->initExistFile();
		$this->assertTrue($this->file->write('My second content\n'));
		$this->assertContains('My second content', $this->file->read());
	}

	public function testDelete()
	{
		$this->initDontExistFile();
		$this->assertTrue($this->file->write('content'));
		$this->assertTrue($this->file->exists());
		$this->file->delete();
		$this->assertFalse($this->file->exists());
		$this->assertFalse($this->file->getSize());
	}

	public function testMove()
	{
		$this->file = new File('data/files/to-be-moved.txt');
		$this->file->write('my content');

		$this->file->move(new Directory('data'));
		$this->assertEquals('data', $this->file->getDirectory()->getPath());
		$this->assertTrue($this->file->exists());
		$this->file->delete();
	}

	public function testMoveWithDirectoryNotExist()
	{
		$this->file = new File('data/files/to-be-moved2.txt');
		$this->file->write('my content');

		$inexist = new Directory('data/inexist');
		$this->file->move($inexist);
		$this->assertEquals('data/inexist', $this->file->getDirectory()->getPath());
		$this->assertTrue($this->file->exists());
		$this->file->delete();
		$inexist->delete();
	}

	public function testRename()
	{
		$this->file = new File('data/files/to-be-rename.txt');
		$this->file->write('I will receive a new name');
		$this->file->rename('renamed-file');

		$this->assertEquals('data/files/renamed-file.txt', $this->file->getPath());
		$this->assertTrue($this->file->exists());
		$this->file->delete();
	}

	public function testRenameDot()
	{
		$this->file = new File('data/files/to-be-rename.txt');
		$this->file->write('I will receive a new name');
		$this->file->rename('renamed.file');

		$this->assertEquals('data/files/renamed.file.txt', $this->file->getPath());
		$this->assertTrue($this->file->exists());
		$this->file->delete();
	}

	public function testRenameWithExtension()
	{
		$this->file = new File('data/files/to-be-rename.txt');
		$this->file->write('I will receive a new name');
		$this->file->rename('renamed-file', 'html');

		$this->assertEquals('data/files/renamed-file.html', $this->file->getPath());
		$this->assertTrue($this->file->exists());
		$this->file->delete();
	}

	/** @expectedException Exception */
	public function testRenameWithSpecialChar()
	{
		$this->file = new File('data/files/to-be-rename.txt');
		$this->file->rename('renamed-special*-file');
	}

	/** @expectedException Exception */
	public function testRenameWithSlash()
	{
		$this->file = new File('data/files/to-be-rename.txt');
		$this->file->rename('invalid/name');
	}

	private function initExistFile()
	{
		$this->file = new File('data/files/exist.html');
	}

	private function initDontExistFile()
	{
		$this->file = new File('data/files/not-exist.html');
	}

	private function initEmptyFile()
	{
		$this->file = new File('data/files/empty.html');
	}
}
