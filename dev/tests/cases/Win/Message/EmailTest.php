<?php

namespace Win\Message;

use PHPUnit_Framework_TestCase;
use Win\File\Directory;
use Win\Mvc\Block;
use Win\Request\Server;

class EmailTest extends PHPUnit_Framework_TestCase {

	public function testGetSubject() {
		$email = new Email();
		$email->setSubject('My Subject');
		$this->assertEquals('My Subject', $email->getSubject());
	}

	public function testGetFrom() {
		$email = new Email();
		$email->setFrom('my@email.com');
		$this->assertEquals('my@email.com', $email->getFrom());
	}

	public function testGetFromName() {
		$email = new Email();
		$email->setFrom('my@email.com', 'My Name');
		$this->assertEquals('my@email.com', $email->getFrom());
		$this->assertEquals('My Name', $email->getFromName());
	}

	public function testAddAddress() {
		$email = new Email();
		$email->addAddress('first@email.com', 'First Name');
		$email->addAddress('second@email.com', 'Second Name');
		$addresses = $email->getAdresses();

		$this->assertEquals(2, count($addresses));
		$this->assertTrue(key_exists('first@email.com', $addresses));
		$this->assertTrue(key_exists('second@email.com', $addresses));
		$this->assertFalse(key_exists('third@email.com', $addresses));
	}

	public function testReplyTo() {
		$email = new Email();
		$email->addAddress('first@email.com', 'First Name');
		$email->addReplyTo('first@reply.com', 'First Reply');
		$email->addReplyTo('second@reply.com', 'Second Reply');
		$replyAddresses = $email->getReplyToAddresses();

		$this->assertEquals(2, count($replyAddresses));
		$this->assertTrue(key_exists('first@reply.com', $replyAddresses));
		$this->assertTrue(key_exists('second@reply.com', $replyAddresses));
		$this->assertFalse(key_exists('first@email.com', $replyAddresses));
	}

	public function testContentString() {
		$email = new Email();
		$email->setContent('My body in string mode');
		$this->assertEquals('My body in string mode', $email->getContent());
	}

	public function testContentNofFoundBlock() {
		$email = new Email();
		$body = new Block('this-block-doent-exist');
		$email->setContent($body);

		$this->assertTrue($email->getContent() instanceof Block);
		$this->assertEquals('', $email->getContent());
	}

	public function testContentBlock() {
		$email = new Email();
		$body = new Block('email/content/first');
		$email->setContent($body);

		$this->assertTrue($email->getContent() instanceof Block);
		$this->assertEquals('My first content', (string) $body);
	}

	public function testNotFoundLayout() {
		$email = new Email();
		$email->setLayout('dont-exists');
		$this->assertEquals($email->__toString(), '');
	}

	public function testDefaultLayout() {
		$email = new Email();
		$this->assertEquals($email->__toString(), 'My default main (with content) ');
	}

	public function testCustomtLayout() {
		$email = new Email();
		$email->setLayout('custom-layout');
		$this->assertTrue($this->findString($email->__toString(), 'My custom layout'));
	}

	public function testLayoutWithContent() {
		$email = new Email();
		$email->setLayout('main');
		$email->setContent(new Block('email/content/first'));
		$this->assertTrue($this->findString($email->__toString(), 'My first content'));
	}

	public function testSend() {
		$email = new Email();
		$email->setContent('My email content');
		$email->setLanguage('pt-br');
		$send = $email->send();
		$this->assertTrue($send);
		$this->assertNull($email->getError());
	}

		public function testSendErrorLocalHost() {
		$email = new Email();
		Email::$sendOnLocalHost = true;
		$email->setContent('My email content');
		$send = $email->send();
		$this->assertFalse($send);
		$this->assertNotNull($email->getError());
		Email::$sendOnLocalHost = false;
	}

	public function testSendSaveFile() {
		$dir = new Directory('data/email');
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

	/** @return boolean */
	private function findString($s1, $s2) {
		return (strpos($s1, $s2) !== false);
	}

}