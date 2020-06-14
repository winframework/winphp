<?php

namespace Win\Common;

use PHPUnit\Framework\TestCase;

class DependenceInjectorTest extends TestCase
{
	const FAKE_SEGMENTS = ['FAKE SEGMENTS'];

	public function tearDown()
	{
		DependenceInjector::$container = [];
	}

	public function testInstance()
	{
		$obj = MyClass::instance();

		$this->assertEquals('Win\\Common\\MyClass', get_class($obj));
	}

	public function testInstanceGetClassDi()
	{
		MyClass::instance();
		DependenceInjector::$container = [
			'Win\\Common\\MyClass' => 'Win\\Common\\MyClass2',
		];

		$obj = MyClass::instance();
		$obj2 = MyClass::instance('2');

		$this->assertEquals('Win\\Common\\MyClass', get_class($obj));
		$this->assertEquals('Win\\Common\\MyClass2', get_class($obj2));
	}
}
