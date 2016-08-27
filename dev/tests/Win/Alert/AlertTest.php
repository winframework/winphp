<?php

namespace Win\Alert;

use Win\Alert\AlertError;

class AlertTest extends \PHPUnit_Framework_TestCase {

	public function testGetMessage() {
		$instance = new AlertError('My Alert');
		$this->assertEquals('My Alert', $instance->message);
	}

	public function testGetType() {
		$instance = new AlertError('My Error msg');
		$this->assertEquals('danger', $instance->type);

		$instance2 = new AlertSuccess('My Success msg');
		$this->assertEquals('success', $instance2->type);
	}

	public function testAddAlertOnSession() {
		ob_start();
		Session::showAlerts();
		new AlertError('My Alert 01');
		new AlertError('My Alert 02');
		$this->assertEquals(2, count(Session::getAlerts()));

		Session::showAlerts();
		$this->assertEquals(0, count(Session::getAlerts()));
		ob_end_clean();
	}

}
