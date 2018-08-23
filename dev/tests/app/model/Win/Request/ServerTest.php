<?php

namespace Win\Request;

class ServerTest extends \PHPUnit_Framework_TestCase {

	public function testGetName() {
		$this->assertTrue(is_string(Server::getName()));
	}

	public function testIsLocalhost() {
		$this->assertTrue(Server::isLocalHost());
	}

}
