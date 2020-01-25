<?php

namespace Win\Common\Utils;

use PHPUnit\Framework\TestCase;
use Win\Application;
use Win\Request\Url;

class MenuTest extends TestCase
{
	public function testActive()
	{
		Url::instance()->setUrl('index');

		$this->assertEquals('active', Menu::active('index'));
		$this->assertEquals('', Menu::active('other-page'));
	}

	public function testActiveParam()
	{
		Url::instance()->setUrl('my-page/my-action/my-last-param');

		$this->assertEquals('active', Menu::active('my-page'));
		$this->assertEquals('active', Menu::active('my-page/my-action'));
		$this->assertEquals('active', Menu::active('my-page/my-action/'));
		$this->assertEquals('active', Menu::active('my-page/my-action/my-last-param'));

		$this->assertEquals('', Menu::active('other-page'));
		$this->assertEquals('', Menu::active('my-page/other-action'));
		$this->assertEquals('', Menu::active('my-page/my-acti'));
		$this->assertEquals('', Menu::active('my-page/my-action/my-last'));
	}

	public function testMultiActive()
	{
		Url::instance()->setUrl('my-page/my-action/my-last-param');

		$this->assertEquals('active', Menu::active('my-page'));
		$this->assertEquals('active', Menu::active('first-page', 'my-page', 'last-page'));
		$this->assertEquals('active', Menu::active('first-page', 'my-page/my-action', 'last-page'));
		$this->assertEquals('', Menu::active('first-page', 'my-page/other-action', 'last-page'));
		$this->assertEquals('', Menu::active('first-page', 'second-page', 'last-page'));
	}
}
