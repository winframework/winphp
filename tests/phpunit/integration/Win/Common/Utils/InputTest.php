<?php

namespace Win\Common\Utils;

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
		$this->assertNull(Input::file('not-exist'));
	}

	public function testIsset()
	{
		$_POST['exist'] = 1;
		$this->assertEquals(true, Input::isset('exist'));
		$this->assertEquals(false, Input::isset('not-exist'));
	}

	public function testPost()
	{
		$_POST['not-exist'] = 1;
		$this->assertNull(Input::post('not-exist'));
		$value = Input::post('not-exist', FILTER_DEFAULT, 'my default post');
		$this->assertEquals($value, 'my default post');
	}

	public function testPostArray()
	{
		$this->assertTrue(is_array(Input::postArray('not-exist')));
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
		$this->assertNull(Input::post('not-exist'));
		$value = Input::get('not-exist', FILTER_DEFAULT, 'my default get');
		$this->assertEquals($value, 'my default get');
	}

	public function testServer()
	{
		$this->assertNotNull(Input::server('HTTP_USER_AGENT'));
		$this->assertNotNull(Input::server('SERVER_PORT'));
	}
}
