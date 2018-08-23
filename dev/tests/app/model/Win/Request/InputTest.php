<?php

namespace Win\Request;

class InputTest extends \PHPUnit_Framework_TestCase {

	public function testProtocol() {
		$this->assertTrue(strlen(Input::protocol()) > 0);
		$this->assertTrue(strpos(Input::protocol(), 'http') !== false);
	}

	public function testFile() {
		$this->assertNull(Input::file('not-exists'));
	}

	public function testPost() {
		$_POST['not-exist'] = 1;
		$this->assertNull(Input::post('not-exists'));
	}

	public function testPostDefault() {
		$value = Input::post('not-exists', FILTER_DEFAULT, 'my default post');
		$this->assertEquals($value, 'my default post');
	}

	public function testGet() {
		$_GET['not-exist'] = 2;
		$this->assertNull(Input::post('not-exists'));
	}

	public function testGetDefault() {
		$value = Input::get('not-exists', FILTER_DEFAULT, 'my default get');
		$this->assertEquals($value, 'my default get');
	}

	public function testServer() {
		$this->assertNotNull(Input::server('HTTP_USER_AGENT'));
		$this->assertNotNull(Input::server('SERVER_PORT'));
	}

}
