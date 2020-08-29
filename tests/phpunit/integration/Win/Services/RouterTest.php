<?php

namespace Win\Services;

use App\Controllers\MyModule\IndexController;
use PHPUnit\Framework\TestCase;
use Win\Application;
use Win\Services\Router;

class RouterTest extends TestCase
{
	public static $routes = [
		'index' => 'IndexController@index',
		'index/([0-9])/(.*)/teste' => 'IndexController@index',
	];

	const NAMESPACE = 'App\\Controller\\';

	public static function setUpBeforeClass(): void
	{
		Router::instance()->add(static::NAMESPACE, static::$routes);
	}

	public function testGetDestination()
	{
		$router = Router::instance();
		Router::instance()->relativeUrl = 'index';

		$expected = [static::NAMESPACE . 'IndexController', 'index'];
		$this->assertEquals($expected, Router::instance()->getDestination());
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

		$expected = [static::NAMESPACE . 'IndexController', 'index', ...$args];
		$this->assertEquals($expected, $router->getDestination());
	}

	public function testGetDestinationNamespace()
	{
		$router = Router::instance();
		$url = 'teste';
		$namespace = 'My\\Custom\\Namespace\\';
		$controller = 'My\\CustomController';
		$router->relativeUrl = $url;

		$router->add($namespace, [$url => $controller . '@index']);

		$this->assertEquals($namespace . $controller, $router->getDestination()[0]);
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
		$data = [1, 2];

		ob_start();
		$app->run(IndexController::class, 'index');
		$sum = ob_get_clean();
		$router = Router::instance();
		$router->__construct();

		$this->assertEquals('my-module-index', $router->page);
		$this->assertEquals('index', $router->action);
	}
}
