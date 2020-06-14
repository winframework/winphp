<?php

namespace Win\Request;

use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
	public function testFormat()
	{
		$link = 'my-custom-link';
		$this->assertEquals('my-custom-link/', Url::format($link));

		$link = 'my-custom-link/';
		$this->assertEquals('my-custom-link/', Url::format($link));
	}

	public function testInit()
	{
		$_SERVER['REQUEST_URI'] = 'my-page';
		$_SERVER['HTTP_HOST'] = 'http://localhost/teste';
		$_SERVER['SCRIPT_NAME'] = '';
		$_SERVER['HTTPS'] = false;
		Url::init();
		$this->assertEquals('http', Url::$protocol);
		$this->assertEquals('my-page', Url::$path);
	}

	public function testInitNoHost()
	{
		$_SERVER['REQUEST_URI'] = 'my-page';
		$_SERVER['HTTP_HOST'] = '';
		$_SERVER['SCRIPT_NAME'] = '';
		$_SERVER['HTTPS'] = false;
		Url::init();
		$this->assertEquals(null, Url::$path);
	}

}
