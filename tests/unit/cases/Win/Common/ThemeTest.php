<?php

namespace Win\Common;

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

	public function testCustomThemeWithValidTemplate()
	{
		$template = new Template('exemplo');
		$this->theme->set('custom');
		$this->assertContains(
			'templates/themes/custom/exemplo',
			$this->theme->getTemplatePath($template)
		);
	}

	public function testCustomThemeWithInValidTemplate()
	{
		$template = new Template('file-not-found');
		$this->theme->set('custom');
		$this->assertContains(
			'themes/default/file-not-found',
			$this->theme->getTemplatePath($template)
		);
	}

	public function testInvalidThemeWithValidTemplate()
	{
		$template = new Template('exemplo');
		$this->theme->set('theme-not-found');
		$this->assertContains(
			'themes/default/exemplo',
			$this->theme->getTemplatePath($template)
		);
	}
}
