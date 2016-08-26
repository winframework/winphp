<?php

namespace Win\Helper;

use Win\Helper\Url;

class UrlTest extends \PHPUnit_Framework_TestCase {

	public function testFormat() {
		Url::instance()->setSufix('/');
		$link = 'my-custom-link';
		$formatedLink = Url::instance()->format($link);
		$this->assertEquals($formatedLink, 'my-custom-link/');

		$linkBar = 'my-custom-link/';
		$formatedLinkBar = Url::instance()->format($linkBar);
		$this->assertEquals($formatedLinkBar, 'my-custom-link/');
	}

	public function testFormatExtension() {
		Url::instance()->setSufix('.html');
		$link2 = 'my-custom-link-with-extension';
		$formatedLink2 = Url::instance()->format($link2);
		$this->assertEquals($formatedLink2, 'my-custom-link-with-extension.html');
	}

}
