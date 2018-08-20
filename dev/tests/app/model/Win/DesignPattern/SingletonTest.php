<?php

namespace Win\DesignPattern;

use Win\Mvc\Application;

class SuperDemoSingleton {

	use Singleton;

	public $value = 10;

	public function getMyClass() {
		return $this->getClassDi();
	}

}

class DemoSingleton extends SuperDemoSingleton {

	public $value = 20;

}

class SingletonTest extends \PHPUnit_Framework_TestCase {

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

}
