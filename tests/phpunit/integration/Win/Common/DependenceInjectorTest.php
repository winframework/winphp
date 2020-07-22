<?php

namespace Win\Common;

use PHPUnit\Framework\TestCase;
use Win\Common\DependenceInjector as DI;
use Win\Common\Traits\InjectableTrait;

class DIParent
{
	public function __construct(DIChild $child)
	{
		$this->child = $child;
	}
}

class DIChild
{
	use InjectableTrait;

	public function __construct(DIGrandChild $grandChild)
	{
		$this->grandChild = $grandChild;
	}
}

class DIGrandChild
{
}

class AliasDIChild extends DIChild
{
}


class DependenceInjectorTest extends TestCase
{
	public function setUp(): void
	{
		DI::$container = [];
	}

	public function tearDown(): void
	{
		DI::$container = [];
	}

	public function testMake()
	{
		$parent = DI::make('Win\\Common\\DIParent');
		$this->assertInstanceOf(DIParent::class, $parent);
		$this->assertInstanceOf(DIChild::class, $parent->child);
		$this->assertInstanceOf(DIGrandChild::class, $parent->child->grandChild);
	}

	public function testMakeAlias()
	{
		DI::$container = [
			DIChild::class => AliasDIChild::class,
		];

		$parent = DI::make(DIParent::class);

		$this->assertInstanceOf(DIParent::class, $parent);
		$this->assertInstanceOf(AliasDIChild::class, $parent->child);
		$this->assertInstanceOf(AliasDIChild::class, DIChild::instance());
	}
}
