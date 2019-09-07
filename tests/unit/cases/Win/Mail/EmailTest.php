<?php

namespace Win\Mail;

use PHPUnit\Framework\TestCase;
use Win\Filesystem\Directory;
use Win\Request\Server;

class EmailTest extends TestCase
{
	public function testGetSubject()
	{
		$email = new Email();
		$email->setSubject('My Subject');
		$this->assertEquals('My Subject', $email->getSubject());
	}

	public function testGetFrom()
	{
		$email = new Email();
		$email->setFrom('my@email.com');
		$this->assertEquals('my@email.com', $email->getFrom());
	}

	public function testGetFromName()
	{
		$email = new Email();
		$email->setFrom('my@email.com', 'My Name');
		$this->assertEquals('my@email.com', $email->getFrom());
		$this->assertEquals('My Name', $email->getFromName());
	}

	public function testAddAddress()
	{
		$email = new Email();
		$email->addTo('first@email.com', 'First Name');
		$email->addTo('second@email.com', 'Second Name');
		$addresses = $email->getTo();

		$this->assertEquals(2, count($addresses));
		$this->assertTrue(key_exists('first@email.com', $addresses));
		$this->assertTrue(key_exists('second@email.com', $addresses));
		$this->assertFalse(key_exists('third@email.com', $addresses));
	}

	public function testReplyTo()
	{
		$email = new Email();
		$email->addTo('first@email.com', 'First Name');
		$email->addReplyTo('first@reply.com', 'First Reply');
		$email->addReplyTo('second@reply.com', 'Second Reply');
		$replyAddresses = $email->getReplyTo();

		$this->assertEquals(2, count($replyAddresses));
		$this->assertTrue(key_exists('first@reply.com', $replyAddresses));
		$this->assertTrue(key_exists('second@reply.com', $replyAddresses));
		$this->assertFalse(key_exists('first@email.com', $replyAddresses));
	}

	public function testContentString()
	{
		$email = new Email();
		$email->setContent('My body in string mode');
		$this->assertEquals('My body in string mode', $email->getContent());
	}

	public function testContentNofFoundBlock()
	{
		$email = new Email();
		$body = new EmailContent('html/this-block-not-exist');
		$email->setContent($body);

		$this->assertTrue($email->getContent() instanceof EmailContent);
		$this->assertEquals('', $email->getContent());
	}

	public function testContentBlock()
	{
		$email = new Email();
		$body = new EmailContent('html/first');
		$email->setContent($body);

		$this->assertTrue($email->getContent() instanceof EmailContent);
		$this->assertEquals('My first content', (string) $body);
	}

	public function testNotFoundLayout()
	{
		$email = new Email();
		$email->setLayout('not-exist');
		$this->assertEquals($email->__toString(), '');
	}

	public function testDefaultLayout()
	{
		$string = (new Email())->__toString();
		$this->assertEquals($string, 'My default main (with content) ');
	}

	public function testCustomLayout()
	{
		$email = new Email('custom-layout');
		$this->assertContains('My custom layout', $email->__toString());
	}

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
