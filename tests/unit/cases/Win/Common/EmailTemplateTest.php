<?php

namespace Win\Common;

use PHPUnit\Framework\TestCase;
use Win\ApplicationTest;
use Win\Models\Email;

class EmailTemplateTest extends TestCase
{

	public function testConstruct()
	{
		$body = 'My first content';
		$email = new Email();
		ApplicationTest::newApp();
		$template = new EmailTemplate($email, 'first');

		$this->assertContains($body, (string) $template);
	}
}
