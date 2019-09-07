<?php

namespace Win\Request;

use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
	public function testFormat()
	{
		Url::instance()->setSuffix('/');
		$link = 'my-custom-link';
		$formatedLink = Url::instance()->format($link);
		$this->assertEquals('my-custom-link/', $formatedLink);

		$linkBar = 'my-custom-link/';
		$formatedLinkBar = Url::instance()->format($linkBar);
		$this->assertEquals('my-custom-link/', $formatedLinkBar);
	}

	public function testFormatExtension()
	{
		Url::instance()->setSuffix('.html');
		$link2 = 'my-custom-link-with-extension';
		$formatedLink2 = Url::instance()->format($link2);
		$this->assertEquals('my-custom-link-with-extension.html', $formatedLink2);
	}

	public function testGetUrl()
	{
		Url::instance()->setSuffix('/');
		Url::instance()->setUrl('my-page/subpage');
		$url = Url::instance()->getUrl();
		$this->assertContains('my-page/subpage', $url);
	}

	public function testGetUrlNull()
	{
		Url::instance('new')->setSuffix('');
		$_SERVER['HTTP_HOST'] = true;

		$url = Url::instance('new')->getUrl();
		$this->assertEquals('', $url);
	}

	public function testGetFragments()
	{
		Url::instance()->setSuffix('/');
		Url::instance()->setUrl('my-page/subpage');
		$fragments = Url::instance()->getSegments();

		$this->assertEquals('my-page', $fragments[0]);
		$this->assertEquals('subpage', $fragments[1]);
	}
}
