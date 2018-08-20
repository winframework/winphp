<?php

namespace Win\Mvc;

use Win\Helper\Url;
use Win\Mvc\Router;
use Win\Mvc\Application;

class RouterTest extends \PHPUnit_Framework_TestCase {

	public function testThereIsNoRouters() {

		Url::instance()->setUrl('my-page/my-action/my-param/');
		$config = [];
		$app = new Application($config);
		$app->controller->load();

		$this->assertEquals('404', $app->getPage());
		$this->assertFalse(Router::instance()->hasCustomUrl());
		$this->assertEquals([null, null], Router::instance()->getCustomUrl());
	}

	public function testThereIsOneRouter() {
		$app = new Application();
		Url::instance()->setUrl('my-page/my-action/my-param/');
	}

}
