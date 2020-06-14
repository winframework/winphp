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
		$this->assertStringContainsString('id="email-body"', (string) $email);

		$email = new Email(static::VALID, [], 'layout-not-found');
		$this->assertStringContainsString('id="email-body"', (string) $email);
	}
}
