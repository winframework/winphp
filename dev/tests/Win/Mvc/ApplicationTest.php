<?php

namespace Win\Mvc;

use Win\Helper\Url;
use Win\Mvc\Application;
use Win\Request\Server;

class ApplicationTest extends \PHPUnit_Framework_TestCase {

	public function testGetInstanceApp() {
		Url::instance()->setUrl('index');
		$app = new Application();
		$this->assertTrue($app instanceof Application);
		$this->assertTrue(Application::app() instanceof Application);
	}

	public function testGetName() {
		$config = ['name' => 'My custom Application Name'];
		$app = new Application($config);

		$this->assertEquals('My custom Application Name', $app->getName());
		$this->assertEquals($app->getName(), $app->getConfig('name'));
	}

	public function testGetTitle() {
		Url::instance()->setUrl('');
		$app = new Application();
		$this->assertEquals('Index', $app->getTitle());

		Url::instance()->setUrl('my-custom-page');
		$app2 = new Application();
		$this->assertEquals('My Custom Page', $app2->getTitle());

		Url::instance()->setUrl('my-custom-page/my-action');
		$app3 = new Application();
		$app3->controller->load();
		$this->assertNotEquals('My Custom Page', $app3->getTitle());

		$app->setTitle('My Custom Test Title');
		$this->assertEquals('My Custom Test Title', $app->getTitle());

		$app->pageNotFound();
		$this->assertNotEquals('My Custom Test Title', $app->getTitle());
	}

	public function testGetPage() {
		Url::instance()->setUrl('my-page/my-action/first-param/2nd-param/3rd-param');
		$app = new Application();

		$this->assertEquals('my-page', $app->getPage());
	}

	public function testGetParamDefault() {
		Url::instance()->setUrl('my-page');
		$app = new Application();

		$this->assertEquals('index', $app->getParam(1));
		$this->assertNotEquals('', $app->getParam(1));
	}

	public function testGetAction() {
		Url::instance()->setUrl('my-page/my-action/first-param/2nd-param/3rd-param');
		$app = new Application();

		$this->assertEquals('myAction', $app->controller->getAction());
	}

	public function testGetParam() {
		Url::instance()->setUrl('my-page/my-action/second-param/3rd-param');
		$app = new Application();

		$this->assertEquals('my-page', $app->getParam(0));
		$this->assertEquals('my-action', $app->getParam(1));
		$this->assertEquals('second-param', $app->getParam(2));
		$this->assertEquals('3rd-param', $app->getParam(3));
		$this->assertEquals('', $app->getParam(4));
	}

	public function testIsErrorPage() {
		Url::instance()->setUrl('index');
		$app = new Application();
		$this->assertFalse($app->isErrorPage());

		$app->pageNotFound();
		$this->assertTrue($app->isErrorPage());
	}

	public function testIsHomePage() {
		Url::instance()->setUrl('');
		$app = new Application();
		$this->assertTrue($app->isHomePage());

		Url::instance()->setUrl('index');
		$app2 = new Application();
		$this->assertTrue($app2->isHomePage());
	}

	public function testIsNotHomePage() {
		Url::instance()->setUrl('index');
		$app = new Application();
		$app->pageNotFound();
		$this->assertFalse($app->isHomePage());

		Url::instance()->setUrl('other-page');
		$app2 = new Application();
		$this->assertFalse($app2->isHomePage());
	}

	public function testIsLocalHost() {
		$this->assertEquals(true, Server::isLocalHost());
	}

	public function testGetConfig() {
		$config = ['year' => 2040];
		$app = new Application($config);
		$this->assertEquals(2040, $app->getConfig('year', 3000));
	}

	public function testGetConfigDefault() {
		$configEmpty = [];
		$app2 = new Application($configEmpty);
		$this->assertEquals(3000, $app2->getConfig('year', 3000));
	}

	public function testRun() {
		Url::instance()->setUrl('this-page-doesnt-exist');
		$app = new Application();
		$this->assertNotEquals('404', $app->getPage());

		Url::instance()->setUrl('this-page-doesnt-exist');
		$app2 = new Application();

		ob_start();
		$app2->run();
		ob_end_clean();
		$this->assertEquals('404', $app2->getPage());
	}

	public function testGetUrl() {
		$currentUrl = Url::instance()->format('my-page/my-action/param1/param2');
		Url::instance()->setUrl($currentUrl);
		$app = new Application();
		$this->assertEquals($currentUrl, $app->getUrl());
	}

	public function testValidatePage404() {
		Url::instance()->setUrl('this-url-doent-exist');
		$app = new Application();
		$this->assertEquals('This Url Doent Exist', $app->getTitle());

		Url::instance()->setUrl('404');
		$app2 = new Application();
		$this->assertNotEquals('404', $app2->getTitle());
	}

}
