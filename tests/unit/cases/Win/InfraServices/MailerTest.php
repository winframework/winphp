<?php

namespace Win\InfraServices;

use PHPUnit\Framework\TestCase;
use Win\Common\EmailTemplate;
use Win\Models\Email;
use Win\Repositories\Filesystem;

class MailerTest extends TestCase
{
	public function setUp()
	{
		$fs = new Filesystem();
		$fs->delete('data/emails');
	}

	public function testSend()
	{
		$EmailTemplate = 'My email body';
		$email = new Email();
		$email->setBody($EmailTemplate);

		$mailer = new Mailer(null);
		$mailer->send($email);
	}

	public function testSendWithTemplate()
	{
		$email = new Email('email', []);

		$mailer = new Mailer(null);
		$mailer->send($email);
	}

	public function testSendWithHeaders()
	{
		$EmailTemplate = 'My email body';
		$email = new Email();
		$email->setBody($EmailTemplate);
		$email->addTo('to@john.com', 'John');
		$email->addBcc('bcc@john.com', 'John');
		$email->addCc('cc@john.com', 'John');
		$email->addCc('cc2@john.com', 'Mary');
		$email->addReplyTo('reply@john.com', 'John');

		$mailer = new Mailer(null);
		$mailer->template = 'secondary';
		$mailer->send($email);
	}

	/**
	 * @expectedException \Exception
	 */
	public function testSendErrorLocalHost()
	{
		Mailer::$sendOnLocalHost = true;
		$EmailTemplate = 'My email body';
		$email = new Email();
		$email->setContent('main');

		$mailer = new Mailer(null);
		$mailer->send($email);
		Mailer::$sendOnLocalHost = false;
	}
}
