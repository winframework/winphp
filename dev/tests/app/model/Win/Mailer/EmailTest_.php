<?php

namespace Win\Mailer;

use Win\Mailer\Email;
use Win\Mvc\Block;

class EmailTest extends \PHPUnit_Framework_TestCase {


	public function testGetSubject() {
		$b = new Block('email/content/first');
		$this->assertEquals($b->exists(),'My first');

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

	public function testLayoutWithContent(){
		$email = new Email();
		$email->setLayout('main');
		$email->setContent(new Block('email/content/first'));
		$this->assertTrue($this->findString($email->__toString(), 'My first content'));
	}

	/** @return boolean */
	private function findString($s1, $s2) {
		return (strpos($s1, $s2) !== false);
	}

}
