<?php

namespace Win\Common\Utils;

use PHPUnit\Framework\TestCase;
use Win\Request\Url;

class LinkTest extends TestCase
{
	public function testActive()
	{
		Url::$segments = ['index'];

		$this->assertEquals('active', Link::active('index'));
		$this->assertEquals('', Link::active('other-page'));
	}

	public function testActiveParam()
	{
		Url::$segments = explode('/', 'my-page/my-action/my-last-param');

		$this->assertEquals('active', Link::active('my-page'));
		$this->assertEquals('active', Link::active('my-page/my-action'));
		$this->assertEquals('active', Link::active('my-page/my-action/'));
		$this->assertEquals('active', Link::active('my-page/my-action/my-last-param'));

		$this->assertEquals('', Link::active('other-page'));
		$this->assertEquals('', Link::active('my-page/other-action'));
		$this->assertEquals('', Link::active('my-page/my-acti'));
		$this->assertEquals('', Link::active('my-page/my-action/my-last'));
	}

	public function testMultiActive()
	{
		Url::$segments = explode('/', 'my-page/my-action/my-last-param');

		$this->assertEquals('active', Link::active('my-page'));
		$this->assertEquals('active', Link::active('first-page', 'my-page', 'last-page'));
		$this->assertEquals('active', Link::active('first-page', 'my-page/my-action', 'last-page'));
		$this->assertEquals('', Link::active('first-page', 'my-page/other-action', 'last-page'));
		$this->assertEquals('', Link::active('first-page', 'second-page', 'last-page'));
	}
}
