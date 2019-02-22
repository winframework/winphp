<?php

namespace Win\Singleton;

use PHPUnit\Framework\TestCase;

class DependenceInjectorTest extends TestCase
{
	public function testGetClassDi()
	{
		$class = DependenceInjector::getClassDi(static::class);
		$this->assertEquals($class, static::class);

		DependenceInjector::$container[static::class] = 'My\Test';
		$class = DependenceInjector::getClassDi(static::class);
		$this->assertEquals($class, 'My\Test');
	}
}
