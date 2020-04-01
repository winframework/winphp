<?php

namespace Win\Common;

use PHPUnit\Framework\TestCase;

class TemplateTest extends TestCase
{
	const VALID = 'blocks/custom-block';
	const INVALID = 'this-file-does-not-exit';

	public function testGetFile()
	{
		$template = new Template('main');
		$templateNotExist = new Template('main/not-exist');

		$this->assertContains('main.phtml', $template->getFile());
		$this->assertContains('main/not-exist.phtml', $templateNotExist->getFile());
	}

	public function testParamData()
	{
		$vars = ['a' => 5];
		$templateWithData = new Template('index', $vars);
		$templateNoData = new Template('index');

		$this->assertEquals($vars['a'], $templateWithData->getData('a'));
		$this->assertNotEquals($vars['a'], $templateNoData->getData('a'));
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

		$this->assertEquals($vars['a'], $b->getData('a'));
		$this->assertEquals($vars['b'], $b->getData('b'));
	}

	public function testDataDoNotExist()
	{
		$vars = ['a' => 1, 'b' => 2];
		$b = new Template(static::INVALID, $vars);

		$this->assertEquals($vars['a'], $b->getData('a'));
		$this->assertEquals($vars['b'], $b->getData('b'));
	}

	public function testToString()
	{
		$b = new Template(static::VALID);
		$c = new Template(static::INVALID);

		$this->assertEquals('My custom block HTML', (string) $b);
		$this->assertEquals('', (string) $c);
	}
}
