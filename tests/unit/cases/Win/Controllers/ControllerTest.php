<?php

namespace Win\Controllers;

use App\Controllers\BasicController;
use PHPUnit\Framework\TestCase;
use Win\Application;

class ControllerTest extends TestCase
{
	public function testExtendsController()
	{
		new Application();
		$controller = new BasicController();
		$five = $controller->returnFive();
		$this->assertEquals($five, 5);
	}

	public function testIndex()
	{
		$c = new MyController(10);
		$this->assertEquals(null, $c->index());
	}

	public function testReturnView()
	{
		$a = 10;
		$controller = new MyController($a);
		
		$this->assertEquals($a, $controller->getA());
		$this->assertEquals(null, $controller->getB());
	}
}
