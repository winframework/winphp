<?php

namespace Win\Data;

class ConfigTest extends \PHPUnit_Framework_TestCase {

	public function testGet() {
		$config = ['a' => 1, 'b' => 2];
		Config::load($config);
		$this->assertEquals(1, Config::get('a'));
		$this->assertEquals(2, Config::get('b', 3));
		$this->assertEquals(null, Config::get('c'));
		$this->assertEquals(3, Config::get('c', 3));
	}

	public function testGetArray() {
		$config = ['a' => 1, 'b' => ['first' => 21, 'second' => 22]];
		Config::load($config);

		$this->assertEquals(1, Config::get('a'));
		$this->assertEquals(21, Config::get('b.first'));
		$this->assertEquals(22, Config::get('b.second'));
		$this->assertEquals(null, Config::get('b.third'));
		$this->assertEquals(100, Config::get('b.third', 100));
	}

	public function testSet() {
		Config::set('a', 1);
		Config::set('b', ['first' => 21, 'second' => 22]);

		$this->assertEquals(null, Config::get('b.third'));
		$this->assertEquals(100, Config::get('b.third', 100));
	}

}
