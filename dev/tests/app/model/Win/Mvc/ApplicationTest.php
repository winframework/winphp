<?php

namespace Win\Mvc;

use Win\Request\Url;
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
		$this->assertEquals($app->getName(), $app->getName());
	}

	public function testGetTitle() {
		Url::instance()->setUrl('');
		$app = new Application();
		$app->controller->setTitle('Index');
		$this->assertEquals('Index', $app->view->getTitle());

		Url::instance()->setUrl('my-custom-page');
		$app2 = new Application();
		$this->assertEquals('My Custom Page', $app2->view->getTitle());

		Url::instance()->setUrl('my-custom-page/my-action');
		$app3 = new Application();
		$app3->controller->load();
		$this->assertNotEquals('My Custom Page', $app3->view->getTitle());

		$app->controller->setTitle('My Custom Test Title Controller');
		$app->view->addData('title', 'My Custom Test Title');
		$this->assertEquals('My Custom Test Title', $app->view->getTitle());
	}

	public function testGetTitle404() {
		$app = new Application();
		$app->controller->setTitle('Old title');
		$app->pageNotFound();
		$this->assertNotEquals('Old titlee', $app->view->getTitle());
	}

	public function testGetTitleErrorPage() {
		$app = new Application();
		ErrorPage::$pages[404] = 'Ops! Not found';
		$app->controller->setTitle('Old title');
		$app->pageNotFound();
		$this->assertEquals('Ops! Not found', $app->view->getTitle());
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
		$this->assertEquals('This Url Doent Exist', $app->view->getTitle());

		Url::instance()->setUrl('404');
		$app2 = new Application();
		$this->assertNotEquals('404', $app2->view->getTitle());
	}

}
