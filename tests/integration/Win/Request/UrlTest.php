<?php

namespace Win\Request;

use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
	public function testFormat()
	{
		Url::instance()->suffix = '/';
		$link = 'my-custom-link';
		$formatedLink = Url::instance()->format($link);
		$this->assertEquals('my-custom-link/', $formatedLink);

		$linkBar = 'my-custom-link/';
		$formatedLinkBar = Url::instance()->format($linkBar);
		$this->assertEquals('my-custom-link/', $formatedLinkBar);
	}

	public function testFormatExtension()
	{
		Url::instance()->suffix = '.html';
		$link2 = 'my-custom-link-with-extension';
		$formatedLink2 = Url::instance()->format($link2);
		$this->assertEquals('my-custom-link-with-extension.html', $formatedLink2);
	}

	public function testGetUrl()
	{
		Url::instance()->suffix = '/';
		Url::instance()->setUrl('my-page/subpage');
		$url = Url::instance()->getUrl();
		$this->assertContains('my-page/subpage', $url);
	}

	public function testGetUrlNull()
	{
		Url::instance('new')->suffix = '';
		$_SERVER['HTTP_HOST'] = true;

		$url = Url::instance('new')->getUrl();
		$this->assertEquals('', $url);
	}

	public function testGetSegments()
	{
		Url::instance()->suffix = '/';
		Url::instance()->setUrl('my-page/subpage');
		$segments = Url::instance()->getSegments();

		$this->assertEquals('my-page', $segments[0]);
		$this->assertEquals('subpage', $segments[1]);
	}

}
