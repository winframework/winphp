<?php

namespace Win\Request;

use Win\Data\Config;

class SessionTest extends \PHPUnit_Framework_TestCase {

	public function testGet() {
		Session::set('a', 1);
		Session::set('b', 2);

		$this->assertEquals(1, Session::get('a'));
		$this->assertEquals(2, Session::get('b', 3));
		$this->assertEquals(null, Session::get('c'));
		$this->assertEquals(3, Session::get('c', 3));
	}

	public function testGetArray() {
		$b = ['first' => 21, 'second' => 22];
		Session::set('a', 1);
		Config::set('a', 11);
		Session::set('b', $b);

		$this->assertEquals(1, Session::get('a'));
		$this->assertEquals(21, Session::get('b.first'));
		$this->assertEquals(22, Session::get('b.second'));
		$this->assertEquals(null, Session::get('b.third'));
		$this->assertEquals(100, Session::get('b.third', 100));
	}

}
