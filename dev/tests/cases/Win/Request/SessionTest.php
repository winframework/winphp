<?php

namespace Win\Request;

use PHPUnit_Framework_TestCase;

class SessionTest extends PHPUnit_Framework_TestCase {

	public function testSet() {
		$session = Session::instance();
		$session->set('a', 1);

		$this->assertEquals(1, $session->get('a'));
		$this->assertEquals(null, $session->get('b'));
	}

	public function testSuperGlobal() {
		$_SESSION['default']['a'] = 1;
		$session = Session::instance();
		$session->set('b', 2);

		$this->assertEquals(1, $session->get('a'));
		$this->assertEquals(2, $session->get('b', 3));
	}

	public function testGetArray() {
		$_SESSION['default']['a']['b'] = 12;
		$session = Session::instance();
		$session->set('b', 2);

		$this->assertEquals(12, $session->get('a.b'));
	}

	public function testGet_MultiInstance() {
		$_SESSION['default']['a']['b'] = 1;
		$_SESSION['user']['a']['b'] = 2;
		$session = Session::instance();
		Session::instance('user')->set('b', 3);

		$this->assertEquals(1, $session->get('a.b'));
		$this->assertEquals(2, Session::instance('user')->get('a.b'));
		$this->assertEquals(3, Session::instance('user')->get('b'));
	}

}
