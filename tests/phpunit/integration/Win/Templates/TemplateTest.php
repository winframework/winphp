<?php

namespace Win\Templates;

use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
	const VALID = 'index/index';
	const INVALID = 'this-file-does-not-exit';

	public function testParamData()
	{
		$vars = ['a' => 5];
		$templateWithData = new Template('index', $vars);
		$templateNoData = new Template('index');

		$this->assertEquals($vars['a'], $templateWithData->get('a'));
		$this->assertNotEquals($vars['a'], $templateNoData->get('a'));
	}

	public function testExist()
	{
		$this->assertTrue((new Template(static::VALID))->exists());
		$this->assertFalse((new Template(static::INVALID))->exists());
	}

	public function testLayout()
	{
		$template = new Template(static::VALID, [], 'layout');
		$template2 = new Template(static::VALID, [], 'layout_not_exist');

		$this->assertTrue($template->exists());
		$this->assertTrue($template2->exists());
	}

	public function testData()
	{
		$vars = ['a' => 1, 'b' => 2];
		$b = new Template(static::VALID, $vars);

		$this->assertEquals($vars['a'], $b->get('a'));
		$this->assertEquals($vars['b'], $b->get('b'));
	}

	public function testDataDoNotExist()
	{
		$vars = ['a' => 1, 'b' => 2];
		$b = new Template(static::INVALID, $vars);

		$this->assertEquals($vars['a'], $b->get('a'));
		$this->assertEquals($vars['b'], $b->get('b'));
	}

	/** @expectedException Error */
	public function testThrowError()
	{
		error_reporting(0);
		$vars = ['a' => 1, 'b' => 2];
		$t = new Template('basic/erro-view', $vars);
		$t->__toString();
	}
}
