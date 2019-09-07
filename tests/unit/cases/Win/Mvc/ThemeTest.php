<?php

namespace Win\Mvc;

use PHPUnit\Framework\TestCase;

class ThemeTest extends TestCase
{
	/** @var Theme */
	public $theme;

	public function setUp()
	{
		$this->theme = Theme::instance();
	}

	public function tearDown()
	{
		$this->theme->set(null);
	}

	public function testGet()
	{
		$this->theme = Theme::instance();
		$this->theme->set('my-test');
		$this->assertEquals($this->theme->get(), 'my-test');
		$this->theme->set(null);
	}

	public function testCustomThemeWithValidView()
	{
		$this->theme->set('custom');
		$this->assertContains(
			'themes/custom/views/exemplo',
			$this->theme->getFilePath('exemplo')
		);
	}

	public function testCustomThemeWithInValidView()
	{
		$this->theme->set('custom');
		$this->assertContains(
			'themes/default/views/file-not-found',
			$this->theme->getFilePath('file-not-found')
		);
	}

	public function testInvalidThemeWithValidView()
	{
		$this->theme->set('theme-not-found');
		$this->assertContains(
			'themes/default/views/exemplo',
			$this->theme->getFilePath('exemplo')
		);
	}

	public function testViewSetFile()
	{
		$this->theme->set('custom');
		$view = new View('exemplo');

		$this->assertContains(
			'themes/custom/views/exemplo.phtml',
			$view->getFile()
		);
		$this->assertTrue($view->exists());
	}

	public function testViewSetFileInvalid()
	{
		$this->theme->set('custom');
		$view = new View('not-exist');

		$this->assertContains(
			'themes/default/views/not-exist.phtml',
			$view->getFile()
		);
		$this->assertFalse($view->exists());
	}
}
