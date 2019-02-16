<?php

namespace Win\Mvc;

class ThemeTest extends \PHPUnit\Framework\TestCase {

	public function testget() {
		$theme = Theme::instance();
		$theme->set('my-test');
		$this->assertEquals($theme->get(), 'my-test');
		$theme->set(null);
	}

	public function testGetFilePath() {
		$theme = Theme::instance();
		$theme->set('custom');

		$this->assertContains('custom/views/exemplo', $theme->getFilePath('exemplo'));
		$this->assertContains('default/views/file-not-found', $theme->getFilePath('file-not-found'));
		$theme->set('theme-not-found');
		$this->assertContains('default/views/exemplo', $theme->getFilePath('exemplo'));
		$theme->set(null);
	}

	public function testViewSetFile() {
		$theme = Theme::instance();
		$theme->set('custom');
		$view = new View('exemplo');

		$this->assertContains('app/themes/custom/views/exemplo.phtml', $view->getFile());
		$this->assertTrue($view->exists());
		$theme->set(null);
	}

	public function testViewSetFileInvalid() {
		$theme = Theme::instance();
		$theme->set('custom');
		$view = new View('doesnt-exist');

		$this->assertContains('app/themes/default/views/doesnt-exist.phtml', $view->getFile());
		$this->assertFalse($view->exists());
		$theme->set(null);
	}

}
