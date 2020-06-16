<?php

namespace Win\Common;

use PHPUnit\Framework\TestCase;
use Win\Common\DependenceInjector as DI;


class DIParent
{
	public function __construct(DIChild $child)
	{
		$this->child = $child;
	}
}

class DIChild
{
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
	const FAKE_SEGMENTS = ['FAKE SEGMENTS'];

	public function setUp()
	{
		DI::$container = [];
	}

	public function tearDown()
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
	}
}
