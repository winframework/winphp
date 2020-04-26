<?php

namespace Win\InfraServices;

use PHPUnit\Framework\TestCase;
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
		$body = 'My email body';

		$mailer = new Mailer();
		$mailer->send($body);
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

		$mailer->send($body);
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
