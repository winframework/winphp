<?php

namespace Win\Templates;

use PHPUnit\Framework\TestCase;
use Win\Application;

class EmailTest extends TestCase
{
	const VALID = 'first';

	public function testToString()
	{
		new Application();
		$email = new Email('layout', ['content'=>'']);
		$this->assertStringContainsString('id="email"', $email);

		$email = new Email(static::VALID, [], 'layout-not-found');
		$this->assertStringNotContainsString('id="email"', (string) $email);
	}
}
