<?php

namespace Win\Html\Navigation;

use PHPUnit\Framework\TestCase;
use Win\Mvc\Application;
use Win\Request\Url;

class MenuTest extends TestCase
{
	public function testActive()
	{
		Url::instance()->setUrl('index');
		new Application();

		$this->assertEquals('active', Menu::active('index'));

		Application::app()->setPage('404');
		$this->assertEquals('active', Menu::active('404'));
	}

	public function testActiveParam()
	{
		Url::instance()->setUrl('my-page/my-action/my-last-param');
		new Application();

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
		new Application();

		$this->assertEquals('active', Menu::active(['my-page', 'other-page', 'last-page']));
		$this->assertEquals('active', Menu::active(['first-page', 'my-page', 'last-page']));
		$this->assertEquals('active', Menu::active(['first-page', 'my-page/my-action', 'last-page']));
		$this->assertEquals('', Menu::active(['first-page', 'my-page/other-action', 'last-page']));
		$this->assertEquals('', Menu::active(['first-page', 'second-page', 'last-page']));
	}
}