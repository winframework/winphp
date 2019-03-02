<?php

namespace Win\Request;

use PHPUnit\Framework\TestCase;

class InputTest extends TestCase
{
	public function testProtocol()
	{
		$this->assertEquals('http', Input::protocol());

		$_SERVER['HTTPS'] = true;
		$this->assertEquals('https', Input::protocol());
	}

	public function testFile()
	{
		$this->assertNull(Input::file('not-exists'));
	}

	public function testPost()
	{
		$_POST['not-exist'] = 1;
		$this->assertNull(Input::post('not-exists'));
		$value = Input::post('not-exists', FILTER_DEFAULT, 'my default post');
		$this->assertEquals($value, 'my default post');
	}

	public function testPostArray()
	{
		$this->assertTrue(is_array(Input::postArray('not-exists')));
	}

	public function testPostFile()
	{
		$_FILES['photo']['name'] = 'image.jpg';
		$_FILES['photo']['size'] = 10;
		$this->assertNotNull(Input::file('photo'));
		$this->assertEquals('image.jpg', Input::file('photo')['name']);
	}

	public function testGet()
	{
		$_GET['not-exist'] = 2;
		$this->assertNull(Input::post('not-exists'));
		$value = Input::get('not-exists', FILTER_DEFAULT, 'my default get');
		$this->assertEquals($value, 'my default get');
	}

	public function testServer()
	{
		$this->assertNotNull(Input::server('HTTP_USER_AGENT'));
		$this->assertNotNull(Input::server('SERVER_PORT'));
	}
}
