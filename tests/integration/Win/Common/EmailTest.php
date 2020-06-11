<?php

namespace Win\Common;

use PHPUnit\Framework\TestCase;
use Win\Application;

class EmailTest extends TestCase
{
	const VALID = 'first';

	public function testDefaultLayout()
	{
		new Application();
		$email = new Email(static::VALID, []);
		$this->assertContains('id="email-body"', $email->toHtml());

		$email = new Email(static::VALID, [], 'layout-not-found');
		$this->assertNotContains('id="email-body"', $email->toHtml());
	}
}
