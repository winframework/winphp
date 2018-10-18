<?php

namespace Win\Mvc;

class TemplateTest extends \PHPUnit_Framework_TestCase {

	public function testGetTheme() {
		$template = Template::instance();
		$template->setTheme('my-test');
		$this->assertEquals($template->getTheme(), 'my-test');
		$template->setTheme(null);
	}

	public function testGetFilePath() {
		$template = Template::instance();
		$template->setTheme('custom');

		$this->assertContains('custom/view/exemplo', $template->getFilePath('exemplo'));
		$this->assertContains('default/view/file-not-found', $template->getFilePath('file-not-found'));
		$template->setTheme('theme-not-found');
		$this->assertContains('default/view/exemplo', $template->getFilePath('exemplo'));
		$template->setTheme(null);
	}

	public function testViewSetFile() {
		$template = Template::instance();
		$template->setTheme('custom');
		$view = new View('exemplo');

		$this->assertContains('app/template/custom/view/exemplo.phtml', $view->getFile());
		$this->assertTrue($view->exists());
		$template->setTheme(null);
	}

	public function testViewSetFileInvalid() {
		$template = Template::instance();
		$template->setTheme('custom');
		$view = new View('doesnt-exist');

		$this->assertContains('app/template/default/view/doesnt-exist.phtml', $view->getFile());
		$this->assertFalse($view->exists());
		$template->setTheme(null);
	}

}
