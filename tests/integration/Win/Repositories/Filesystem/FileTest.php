<?php

namespace Win\Repositories\Filesystem;

use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
	/** @var File */
	private $file;

	/** @expectedException \Exception */
	public function testNameEmpty()
	{
		new File('');
	}

	/** @expectedException \Exception */
	public function testNameWithSpecialChar()
	{
		new File('inva$lidâ');
	}

	/** @expectedException \Exception */
	public function testNameWithSpace()
	{
		new File('invalid name');
	}

	/** @expectedException \Exception */
	public function testDirectoryInvalid()
	{
		new File('inva$lidâ/my-file');
	}

	public function testDirectoryValid()
	{
		new File('valid-name/d');

		$this->file = new File('valid-name/');
		$this->assertEquals('', $this->file->getExtension());
		$this->assertEquals('valid-name', $this->file->getName());
	}

	public function testValidPaths()
	{
		new File('7valid10name3');
		new File('_valid-name-');
		new File('.my-file.teste.');
		new File('my-directory/sub-directory/7valid_-10name3.jpg');
		new File('7valid_-10name3.jpg');
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
		$this->file = new File('data/files/not-exist.html');
		$this->assertEquals(false, $this->file->getSize());
	}

	public function testExists()
	{
		$this->initExistFile();
		$this->assertTrue($this->file->exists());
		$this->initNotExistFile();
		$this->assertFalse($this->file->exists());
	}

	public function testGetContent()
	{
		$this->initExistFile();
		$this->assertContains('My second content', $this->file->getContent());
	}
	
	private function initExistFile()
	{
		$this->file = new File('data/files/exist.html');
	}

	private function initNotExistFile()
	{
		$this->file = new File('data/files/not-exist.html');
	}

	private function initEmptyFile()
	{
		$this->file = new File('data/files/empty.html');
	}
}
