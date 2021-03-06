<?php

namespace Win\Services;

use PHPUnit\Framework\TestCase;
use Win\Templates\Email;
use Win\Services\Filesystem;

class MailerTest extends TestCase
{
	/** @var Filesystem */
	protected $fs;

	public function setUp(): void
	{
		$this->fs = new Filesystem();
		$this->fs->delete('data/emails');
	}

	public function tearDown(): void
	{
		$this->fs->delete('data/emails');
	}

	public function testGetSubject()
	{
		$subject = 'My Subject';
		$mailer = Mailer::instance();
		
		$mailer->setSubject($subject);

		$this->assertEquals($subject, $mailer->getSubject());
	}

	public function testSend()
	{
		$body = 'My email body';

		$mailer = Mailer::instance();
		$mailer->send($body);
		$this->assertEquals(1, $this->fs->count('data/emails'));
	}

	public function testSendTemplate()
	{
		$body = new Email('first', [], '');

		$mailer = Mailer::instance();
		$mailer->send($body);
		$this->assertEquals($mailer, $body->mailer);
		$this->assertEquals(1, $this->fs->count('data/emails'));
	}

	public function testSendTemplateLayout()
	{
		$mailer = Mailer::instance();
		$mailer->send([], 'first');

		$this->assertEquals(1, $this->fs->count('data/emails'));
	}

	public function testSendWithHeaders()
	{
		$body = 'My email body';
		$mailer = Mailer::instance();
		$mailer->addTo('to@john.com', 'John');
		$mailer->addBcc('bcc@john.com', 'John');
		$mailer->addCc('cc@john.com', 'John');
		$mailer->addCc('cc2@john.com', 'Mary');
		$mailer->addReplyTo('reply@john.com', 'John');
		$mailer->setFrom('from@john.com', 'Luccas');
		$mailer->setSubject('My subject');
		$mailer->setLanguage('en-Us');

		$mailer->send($body);
		$this->assertEquals(1, $this->fs->count('data/emails'));
	}

	/**
	 * @expectedException \Exception
	 */
	public function testSendErrorLocalHost()
	{
		Mailer::$sendOnLocalHost = true;
		$mailer = Mailer::instance();
		$body = 'My email body';

		$mailer->send($body);
		Mailer::$sendOnLocalHost = false;
	}
}
