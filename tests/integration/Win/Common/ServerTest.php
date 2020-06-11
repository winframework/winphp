<?php

namespace Win\Common;

use PHPUnit\Framework\TestCase;

class ServerTest extends TestCase
{
	public function testGetName()
	{
		$this->assertTrue(is_string(Server::getName()));
	}

	public function testIsLocalhost()
	{
		$this->assertTrue(Server::isLocalHost());
	}
}