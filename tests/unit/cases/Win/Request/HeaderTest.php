<?php

namespace Win\Request;

function header()
{
	return Header::instance()->clear();
}

use PHPUnit\Framework\TestCase;

class HeaderTest extends TestCase
{
	public function testGet()
	{
		Header::instance()->set('location', 'test');
		$this->assertEquals('test', Header::instance()->get('location'));
	}
}
