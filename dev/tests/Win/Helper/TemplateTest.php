<?php

namespace Win\Helper;

class TemplateTest extends \PHPUnit_Framework_TestCase {

	public function testGetTheme() {
		Template::instance()->setTheme('my-test');
		$this->assertEquals(Template::instance()->getTheme(), 'my-test');
	}

}
