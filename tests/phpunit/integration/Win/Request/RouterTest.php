<?php

namespace Win\Request;

use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
	public static $routes = [
		'index' => 'IndexController@index',
		'index/([0-9])/(.*)/teste' => 'IndexController@index',
	];

	const NAMESPACE = 'App\\Controller\\';

	public static function setUpBeforeClass(): void
	{
		Router::add(static::NAMESPACE, static::$routes);
	}

	public function testGetDestination()
	{
		Url::$path = 'index';

		$expected = [static::NAMESPACE . 'IndexController', 'index'];
		$this->assertEquals($expected, Router::getDestination());
	}

	/**
	 * @expectedException \Win\HttpException
	 */
	public function testGetDestinationException()
	{
		Url::$path = 'naoExiste';

		Router::getDestination();
	}

	public function testGetDestinationArgs()
	{
		$args = [5, 'John'];
		Url::$path = 'index/' . implode('/', $args) . '/teste';

		$expected = [static::NAMESPACE . 'IndexController', 'index', ...$args];
		$this->assertEquals($expected, Router::getDestination());
	}

	public function testGetDestinationNamespace()
	{
		$url = 'teste';
		$namespace = 'My\\Custom\\Namespace\\';
		$controller = 'My\\CustomController';
		Url::$path = $url;

		Router::add($namespace, [$url => $controller . '@index']);

		$this->assertEquals($namespace . $controller, Router::getDestination()[0]);
	}
}
