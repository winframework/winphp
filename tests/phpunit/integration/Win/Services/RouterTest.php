<?php

namespace Win\Services;

use App\Controllers\IndexController;
use PHPUnit\Framework\TestCase;
use Win\Application;
use Win\Services\Router;

class RouterTest extends TestCase
{
	public static $routes = [
		'index' => [IndexController::class, 'index'],
		'index/([0-9])/(.*)/teste' => [IndexController::class, 'index'],
	];

	public static function setUpBeforeClass(): void
	{
		Router::instance()->routes = static::$routes;
	}

	public function testGetDestination()
	{
		$router = Router::instance();
		$router->relativeUrl = 'index';

		$expected = [IndexController::class, 'index'];
		$this->assertEquals($expected, $router->getDestination());
	}

	/**
	 * @expectedException \Win\HttpException
	 */
	public function testGetDestinationException()
	{
		$router = Router::instance();
		$router->relativeUrl = 'naoExiste';

		$router->getDestination();
	}

	public function testGetDestinationArgs()
	{
		$router = Router::instance();
		$args = [5, 'John'];
		$router->relativeUrl = 'index/' . implode('/', $args) . '/teste';

		$expected = [IndexController::class, 'index', ...$args];
		$this->assertEquals($expected, $router->getDestination());
	}

	public function testFormat()
	{
		$router = Router::instance();
		$link = 'my-custom-link';
		$this->assertEquals('my-custom-link/', $router->format($link));

		$link = 'my-custom-link/';
		$this->assertEquals('my-custom-link/', $router->format($link));
	}

	public function testConstructor()
	{
		$router = Router::instance();
		$_SERVER['REQUEST_URI'] = 'my-page';
		$_SERVER['HTTP_HOST'] = 'http://localhost/teste';
		$_SERVER['SCRIPT_NAME'] = '';
		$_SERVER['HTTPS'] = false;
		$router->__construct();
		$this->assertEquals('http', $router->protocol);
		$this->assertEquals('my-page', Router::instance()->relativeUrl);
	}

	public function testConstructorNoHost()
	{
		$router = Router::instance();
		$_SERVER['REQUEST_URI'] = 'my-page';
		$_SERVER['HTTP_HOST'] = '';
		$_SERVER['SCRIPT_NAME'] = '';
		$_SERVER['HTTPS'] = false;
		$router->__construct();
		$this->assertEquals(null, $router->relativeUrl);
	}

	public function testPageAction()
	{
		$app = new Application();

		ob_start();
		$app->run(\App\Controllers\MyModule\IndexController::class, 'index');
		ob_get_clean();
		$router = Router::instance();
		$router->__construct();

		$this->assertEquals('my-module-index', $router->page);
		$this->assertEquals('index', $router->action);
	}
}
