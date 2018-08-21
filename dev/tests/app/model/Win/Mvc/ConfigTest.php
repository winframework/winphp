<?php

namespace Win\Mvc;

class ConfigTest extends \PHPUnit_Framework_TestCase {

	public function testGet() {
		$config = ['a' => 1, 'b' => 2];
		Config::init($config);
		$this->assertEquals(1, Config::get('a'));
		$this->assertEquals(2, Config::get('b', 3));
		$this->assertEquals(null, Config::get('c'));
		$this->assertEquals(3, Config::get('c', 3));
	}

}
