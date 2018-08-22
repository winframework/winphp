<?php

namespace Win\Mvc;

class TemplateTest extends \PHPUnit_Framework_TestCase {

	public function testGetTheme() {
		Template::instance();
		Template::instance()->setTheme('my-test');
		$this->assertEquals(Template::instance()->getTheme(), 'my-test');
		Template::instance()->setTheme(null);
	}

}
