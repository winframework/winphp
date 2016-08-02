<?php
namespace Win\Mvc;

use Win\Helper\Url;
use Win\Mvc\Route;
use Win\Mvc\Application;


class RouteTest extends \PHPUnit_Framework_TestCase {

	public function testThereIsNoRoutes() {

		Url::instance()->setUrl('my-page/my-action/my-param/');
		$config = [];
		$app = new Application($config);
		$app->controller->load();

		$this->assertEquals('404', $app->getPage());
		$this->assertFalse(Route::instance()->hasCustomUrl());
		$this->assertEquals([null, null], Route::instance()->getCustomUrl());
	}

	public function testThereIsOneRoute() {
		Url::instance()->setUrl('my-page/my-action/my-param/');
		$config = [
			'route' => [
				'my-page/(.*)' => 'index'
			]
		];

		$app = new Application($config);
		$app->controller->load();

		$this->assertEquals('index', $app->getPage());
		$this->assertTrue(Route::instance()->hasCustomUrl());
		$this->assertEquals(['index', null], Route::instance()->getCustomUrl());
	}

	public function testRouteToPageNotFound() {
		Url::instance()->setUrl('my-page/my-action/my-param/');
		$config = [
			'route' => [
				'other-page' => 'index'
			]
		];

		$app = new Application($config);
		$app->controller->load();

		$this->assertEquals('404', $app->getPage());
		$this->assertFalse(Route::instance()->hasCustomUrl());
	}

	public function testRouteToActionNotFound() {
		Url::instance()->setUrl('my-page/my-action/my-param/');
		$config = [
			'route' => [
				'my-page/(.*)' => 'index/this-action-doeent-exit'
			]
		];

		$app = new Application($config);
		$app->controller->load();

		$this->assertEquals('404', $app->getPage());
	}

	public function testRouteWithCustomParam() {
		Url::instance()->setUrl('my-product/list/id/100');
		$config = [
			'route' => [
				'my-product/(.*)/id/(.*)' => 'index/index/$1/teste/$2'
			]
		];

		$app = new Application($config);
		$app->controller->load();

		$this->assertEquals('index', $app->getPage());
		$this->assertEquals('list', $app->getParam(2));
		$this->assertEquals('teste', $app->getParam(3));
		$this->assertEquals('100', $app->getParam(4));
		$this->assertEquals(['index', 'index', 'list', 'teste', '100'], array_filter(Route::instance()->getCustomUrl()));
	}

}
