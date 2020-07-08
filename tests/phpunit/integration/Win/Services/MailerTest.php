<?php

namespace Win\Services;

use PHPUnit\Framework\TestCase;
use Win\Common\Email;
use Win\Repositories\Filesystem;

class MailerTest extends TestCase
{
	/** @var Filesystem */
	protected $fs;

	public function setUp()
	{
		$this->fs = new Filesystem();
		$this->fs->delete('data/emails');
	}

	public function tearDown()
	{
		$this->fs->delete('data/emails');
	}

	public function testSend()
	{
		$body = 'My email body';

		$mailer = new Mailer();
		$mailer->send($body);
		$this->assertEquals(1, $this->fs->count('data/emails'));
	}

	public function testSendTemplate()
	{
		$body = new Email('first', [], '');

		$mailer = new Mailer();
		$mailer->send($body);
		$this->assertEquals(1, $this->fs->count('data/emails'));
	}

	public function testSendWithHeaders()
	{
		$body = 'My email body';
		$mailer = new Mailer();
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
		$mailer = new Mailer();
		$body = 'My email body';

		$mailer->send($body);
		Mailer::$sendOnLocalHost = false;
	}
}
