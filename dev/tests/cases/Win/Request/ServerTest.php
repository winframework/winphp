<?php

namespace Win\Request;

class ServerTest extends \PHPUnit\Framework\TestCase {

	public function testGetName() {
		$this->assertTrue(is_string(Server::getName()));
	}

	public function testIsLocalhost() {
		$this->assertTrue(Server::isLocalHost());
	}

}
