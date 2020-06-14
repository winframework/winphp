<?php

namespace Win;

use App\Controllers\IndexController;
use PHPUnit\Framework\TestCase;
use Win\Controllers\MyController;
use Win\Request\Url;

class ApplicationTest extends TestCase
{
	const URL = 'demo/my-action/second-param/3rd-param/';

	/** @var Application */
	private static $app;

	public static function setUpBeforeClass()
	{
		static::$app = static::newApp(static::URL);
	}

	public static function newApp($url = 'index')
	{
		$_SERVER['REQUEST_URI'] = $url;
		$_SERVER['HTTP_HOST'] = 'http://localhost';
		$_SERVER['SCRIPT_NAME'] = '';
		return new Application();
	}

	public function testApp()
	{
		$this->assertTrue(static::$app instanceof Application);
		$this->assertTrue(Application::app() instanceof Application);
	}

	public function testGetPage()
	{
		$app = static::newApp('demo');
		$this->assertEquals('demo', static::$app->getPage());

		$app = static::newApp('index');
		$this->assertEquals('index', $app->getPage());
	}

	public function testIsHomePage()
	{
		$app = new Application();
		$app->controller = new IndexController();
		$this->assertTrue($app->isHomePage());
	}

	public function testIsNotHomePage()
	{
		$app = new Application();
		$app->controller = new MyController();
		$this->assertFalse($app->isHomePage());
	}


	/** @expectedException \Win\HttpException */
	public function testRunController404()
	{
		$app = new Application();
		$app->run('App\\Controllers\\InvalidController', 'index');
	}

	/** @expectedException \Win\HttpException */
	public function testRunAction404()
	{
		$app = new Application();
		$app->run('App\\Controllers\\IndexController', 'actionNotFound');
	}

	public function testRun()
	{
		$app = new Application();
		$data = [1, 2];

		ob_start();
		$app->run('Win\\Controllers\\MyController', 'sum', $data);
		$sum = ob_get_clean();

		$this->assertEquals(array_sum($data), $sum);
	}

	public function testRunView()
	{
		$app = new Application();
		$data = [1, 2];

		ob_start();
		$app->run('Win\\Controllers\\MyController', 'index', $data);

		$this->assertStringContainsString('Esta é uma view simples', ob_get_clean());
	}

	/**
	 * @expectedException \Win\HttpException
	 */
	public function testErrorPage500()
	{
		$app = new Application();
		$app->errorPage(500);
	}

	/**
	 * @expectedException \Win\HttpException
	 */
	public function testPageNotFound()
	{
		$app = new Application();
		$app->page404();
	}
}
