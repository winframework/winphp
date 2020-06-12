<?php

namespace Win\Request;

use PHPUnit\Framework\TestCase;
use Win\Application;

class RouterTest extends TestCase
{
	public static $routes = [
		'index/' => 'IndexController@index',
		'index/([0-9])/(.*)/teste' => 'IndexController@index',
	];

	const NAMESPACE = 'App\\Controller\\';

	public static function setUpBeforeClass()
	{
		Router::addRoutes(static::NAMESPACE, static::$routes);
	}

	/**
	 * @expectedException \Win\HttpException
	 */
	public function testRoute404()
	{
		Url::instance()->setUrl('naoExiste');

		Router::getDestination();
	}

	public function testRouteValid()
	{
		Url::instance()->setUrl('index');

		$expected = [static::NAMESPACE . 'IndexController', 'index', []];
		$this->assertEquals($expected, Router::getDestination());
	}

	public function testRouteValidArgs()
	{
		$args = [5, 'John'];
		Url::instance()->setUrl('index/' . implode('/', $args) . '/teste');

		$expected = [static::NAMESPACE . 'IndexController', 'index', $args];
		$this->assertEquals($expected, Router::getDestination());
	}

	public function testAddRoutesNamespaces()
	{
		$url = 'teste';
		$namespace = 'My\\Custom\\Namespace\\';
		$controller = 'My\\CustomController';
		Url::instance()->setUrl($url);


		Router::addRoutes($namespace, [$url => $controller . '@index']);

		$this->assertEquals($namespace . $controller, Router::getDestination()[0]);
	}

	/** @expectedException \Win\HttpException */
	public function testCreateController404()
	{
		new Application();
		$data = ['a' => 1, ' b' => 2];
		$destination = ['App\\Controllers\\InvalidController', 'index', $data];
		Router::process($destination);
	}

	/** @expectedException \Win\HttpException */
	public function testCreateAction404()
	{
		new Application();
		$data = ['a' => 1, ' b' => 2];
		$destination = ['App\\Controllers\\IndexController', 'actionNotFound', $data];
		Router::process($destination);
	}

	public function testCreate()
	{
		new Application();
		$data = [1, 2];
		$destination = ['Win\\Controllers\\MyController', 'sum', $data];

		ob_start();
		$sum = Router::process($destination);
		$sum = ob_get_clean();

		$this->assertEquals(array_sum($data), $sum);
	}
}
