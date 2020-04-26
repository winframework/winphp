<?php

namespace Win\Response;

use PHPUnit\Framework\TestCase;
use Win\Application;

class ResponseFactoryTest extends TestCase
{
	/** @expectedException \Win\Response\ResponseException */
	public function testCreateController404() 
	{
		new Application();
		$data = ['a' => 1, ' b' => 2];
		$destination = ['App\\Controllers\\InvalidController', 'index', $data];
		ResponseFactory::send($destination);
	}

	/** @expectedException \Win\Response\ResponseException */
	public function testCreateAction404()
	{
		new Application();
		$data = ['a' => 1, ' b' => 2];
		$destination = ['App\\Controllers\\IndexController', 'actionNotFound', $data];
		ResponseFactory::send($destination);
	}

	public function testCreate()
	{
		new Application();
		$data = [1, 2];
		$destination = ['Win\\Controllers\\MyController', 'sum', $data];

		ob_start();
		$sum = ResponseFactory::send($destination);
		$sum = ob_get_clean();

		$this->assertEquals(array_sum($data), $sum);
	}
}
