<?php

namespace Win\Alert;

use Win\Alert\AlertError;

class SessionTest extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		Session::clearAlerts();
	}

	public function testCountAlerts() {
		ob_start();
		new AlertError('My Alert 01');
		new AlertError('My Alert 02');
		$this->assertEquals(2, count(Session::getAlerts()));

		Session::clearAlerts();
		$this->assertEquals(0, count(Session::getAlerts()));
		ob_end_clean();
	}

	public function testHasAlert() {
		new AlertDefault('Test');
		$this->assertEquals(true, Session::hasAlert());
	}

	public function testHasNoAlert() {
		new AlertDefault('Test');
		Session::clearAlerts();
		$this->assertEquals(false, Session::hasAlert());
	}

	public function testGetAlerts() {
		$alert = new AlertError('I am the firt error');
		$alerts = Session::getAlerts();
		$this->assertEquals($alert->message, $alerts[0]->message);
	}

	public function testShowAnything() {
		ob_start();
		new AlertError('My Error msg');
		Session::showAlerts();
		$content = ob_get_contents();
		ob_end_clean();
		$this->assertNotEmpty($content);
	}

	public function testShowNothing() {
		ob_start();
		Session::showAlerts();
		$content = ob_get_contents();
		ob_end_clean();
		$this->assertEmpty($content);
	}

}
