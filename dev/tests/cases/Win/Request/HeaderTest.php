<?php

namespace Win\Request;

use PHPUnit_Framework_TestCase;

class HeaderTest extends PHPUnit_Framework_TestCase {

	public function testGet() {
		Header::instance()->set('location', 'test');
		$this->assertEquals('test', Header::instance()->get('location'));
	}

}
