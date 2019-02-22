<?php

namespace Win\Singleton;

use PHPUnit\Framework\TestCase;
use Win\Mvc\Application;

class SingletonTraitTest extends TestCase
{
	public function testInstance()
	{
		$instance = SuperDemoSingleton::instance();
		$this->assertInstanceOf(SuperDemoSingleton::class, $instance);

		$instance2 = SuperDemoSingleton::instance();
		$this->assertNotInstanceOf(Application::class, $instance2);
	}

	public function testGetClassDi()
	{
		$class = SuperDemoSingleton::instance()->getMyClass();
		$this->assertEquals($class, SuperDemoSingleton::class);
		$this->assertNotEquals($class, Application::class);
	}

	public function testGetOverwriteClassDi()
	{
		DependenceInjector::$container[SuperDemoSingleton::class] = 'My\Teste';
		$class = SuperDemoSingleton::instance()->getMyClass();
		$this->assertEquals($class, 'My\Teste');
		DependenceInjector::$container = [];
	}

	public function testSuperGetValue()
	{
		SuperDemoSingleton::instance()->value = 5;
		$this->assertEquals(5, SuperDemoSingleton::instance()->value);
	}

	public function testGetValue()
	{
		SuperDemoSingleton::instance()->value = 10;
		DemoSingleton::instance()->value = 20;
		$this->assertEquals(10, SuperDemoSingleton::instance()->value);
		$this->assertEquals(20, DemoSingleton::instance()->value);

		DemoSingleton::instance()->value = 5;
		$this->assertEquals(5, DemoSingleton::instance()->value);
		$this->assertEquals(10, SuperDemoSingleton::instance()->value);
	}

	public function testMultiInstance()
	{
		SuperDemoSingleton::instance()->value = 10;
		SuperDemoSingleton::instance('super1')->value = 20;
		SuperDemoSingleton::instance('super2')->value = 30;

		$this->assertEquals(10, SuperDemoSingleton::instance()->value);
		$this->assertEquals(20, SuperDemoSingleton::instance('super1')->value);
		$this->assertEquals(30, SuperDemoSingleton::instance('super2')->value);
	}
}
