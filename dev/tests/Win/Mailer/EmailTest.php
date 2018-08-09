<?php

namespace Win\Mailer;

use Win\Mailer\Email;
use Win\Mvc\Block;

class EmailTest extends \PHPUnit_Framework_TestCase {

	public function testSetSubject() {
		$email = new Email();
		$email->setSubject('My Subject');
		$this->assertEquals('My Subject', $email->getSubject());
	}

	public function testSetBodyInStringMode() {
		$email = new Email();
		$email->setContent('My body in string mode');
		$this->assertEquals('My body in string mode', $email->getContent());
	}

	public function testSetBodyInBlockMode() {
		$email = new Email();
		$body = new Block('this-block-doent-exist');
		$email->setContent($body);

		$this->assertTrue($email->getContent() instanceof Block);
		$this->assertEquals('', $email->getContent());
	}

}
