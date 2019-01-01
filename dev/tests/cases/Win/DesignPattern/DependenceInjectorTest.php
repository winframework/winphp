<?php

namespace Win\DesignPattern;

class DependenceInjectorTest extends \PHPUnit\Framework\TestCase {

	public function testGetClassDi() {
		$class = DependenceInjector::getClassDi(static::class);
		$this->assertEquals($class, static::class);
	}

	public function testCustomGetClassDi() {
		DependenceInjector::$container[static::class] = 'My\Test';
		$class = DependenceInjector::getClassDi(static::class);
		$this->assertEquals($class, 'My\Test');
	}

}
