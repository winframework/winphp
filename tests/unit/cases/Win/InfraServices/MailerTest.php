<?php

namespace Win\Mail;

use PHPUnit\Framework\TestCase;
use Win\Filesystem\Directory;
use Win\Request\Server;

class MailerTest extends TestCase
{
	public function testLayoutWithContent()
	{
		$email = new Email();
		$email->setLayout('main');
		$email->setContent(new EmailContent('html/first'));
		$this->assertContains('My first content', $email->__toString());
	}

	public function testSend()
	{
		$email = new Email();
		$email->setContent('My email content');
		$email->setLanguage('pt-br');
		$email->send();
	}

	/**
	 * @expectedException \Exception
	 */
	public function testSendErrorLocalHost()
	{
		$email = new Email();
		Email::$sendOnLocalHost = true;
		$email->setContent('My email content');
		$email->send();
		Email::$sendOnLocalHost = false;
	}

	public function testSendSaveFile()
	{
		$dir = new Directory('data/emails');
		$dir->delete();
		$dir->create();
		$this->assertEquals(0, count($dir->getItems()));

		Email::$sendOnLocalHost = false;
		$email = new Email();
		$email->setContent('My email content');
		$email->send();

		if (Server::isLocalHost()) {
			$this->assertEquals(1, count($dir->getItems()));
		}
	}
}
