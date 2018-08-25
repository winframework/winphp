<?php

namespace Win\Request;

class HeaderTest extends \PHPUnit_Framework_TestCase {

	public function testGet() {
		Header::set('location', 'test');
		$this->assertEquals('test', Header::get('location'));
	}

}
