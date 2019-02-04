<?php

namespace Win\Singleton;

use Win\Mvc\Application;

class SingletonTraitTest extends \PHPUnit\Framework\TestCase {

	public function testInstance() {
		$instance = SuperDemoSingleton::instance();
		$this->assertTrue($instance instanceof SuperDemoSingleton);

		$instance2 = SuperDemoSingleton::instance();
		$this->assertFalse($instance2 instanceof Application);
	}

	public function testGetClassDi() {
		$class = SuperDemoSingleton::instance()->getMyClass();
		$this->assertEquals($class, SuperDemoSingleton::class);
		$this->assertNotEquals($class, Application::class);
	}

	public function testGetOverwriteClassDi() {
		DependenceInjector::$container[SuperDemoSingleton::class] = 'My\Teste';
		$class = SuperDemoSingleton::instance()->getMyClass();
		$this->assertEquals($class, 'My\Teste');
		DependenceInjector::$container = [];
	}

	public function testSuperGetValue() {
		SuperDemoSingleton::instance()->value = 5;
		$this->assertEquals(SuperDemoSingleton::instance()->value, 5);
	}

	public function testGetValue() {
		SuperDemoSingleton::instance()->value = 10;
		DemoSingleton::instance()->value = 20;
		$this->assertEquals(SuperDemoSingleton::instance()->value, 10);
		$this->assertEquals(DemoSingleton::instance()->value, 20);

		DemoSingleton::instance()->value = 5;
		$this->assertEquals(DemoSingleton::instance()->value, 5);
		$this->assertEquals(SuperDemoSingleton::instance()->value, 10);
	}

	public function testMultiInstance() {
		SuperDemoSingleton::instance()->value = 10;
		SuperDemoSingleton::instance('super1')->value = 20;
		SuperDemoSingleton::instance('super2')->value = 30;

		$this->assertEquals(SuperDemoSingleton::instance()->value, 10);
		$this->assertEquals(SuperDemoSingleton::instance('super1')->value, 20);
		$this->assertEquals(SuperDemoSingleton::instance('super2')->value, 30);
	}

}
