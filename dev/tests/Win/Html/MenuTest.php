<?php

namespace Win\Html;

use Win\Mvc\Application;
use Win\Helper\Url;
use Win\Html\Menu;

class MenuTest extends \PHPUnit_Framework_TestCase {

	public function testActive() {
		Url::instance()->setUrl('index');
		new Application();

		$this->assertEquals('active', Menu::active('index'));

		Application::app()->pageNotFound();
		$this->assertEquals('active', Menu::active('404'));
	}

	public function testActiveParam() {
		Url::instance()->setUrl('my-page/my-action/my-last-param');
		new Application();

		$this->assertEquals('active', Menu::active('my-page'));
		$this->assertEquals('', Menu::active('my-page/my-action'));
		$this->assertEquals('active', Menu::active('my-page/my-action/my-last-param'));

		$this->assertEquals('', Menu::active('other-page'));
		$this->assertEquals('', Menu::active('my-page/other-action'));
	}

}
