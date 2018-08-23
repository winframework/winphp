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
		Session::getAlerts();
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

	public function testSetAutoSave() {
		Session::setAutoSave(true);
		new AlertDefault('Test');
		$this->assertEquals(1, count(Session::getAlerts()));

		Session::setAutoSave(false);
		new AlertDefault('Test 2');
		$this->assertEquals(1, count(Session::getAlerts()));
		Session::setAutoSave(true);

		new AlertDefault('Test 3');
		$this->assertEquals(2, count(Session::getAlerts()));
	}

	public function testGetAlertsValue() {
		$alert = new AlertError('I am the first error');

		$alerts = Session::getAlerts();
		$this->assertEquals($alert->message, $alerts[0]->message);
	}

	public function testShowOneAlert() {
		ob_start();
		$msg = new AlertError('My Error msg');
		Session::showAlerts();
		$content = ob_get_contents();
		ob_end_clean();
		ob_start();
		$msg->load();
		$msgContent = ob_get_contents();
		ob_end_clean();
		$this->assertEquals($content, $msgContent);
	}

	public function testShowNothing() {
		ob_start();
		Session::showAlerts();
		$this->assertEmpty(ob_get_clean());
	}

	public function testShowByType() {
		new AlertError('My error msg 1', 'group');
		new AlertSuccess('My success msg', 'group');
		ob_start();
		Session::showAlerts(AlertSuccess::TYPE);

		$this->assertNotEmpty(ob_get_clean());
		$this->assertEquals(1, count(Session::getAlerts(AlertError::TYPE)));
		$this->assertEquals(0, count(Session::getAlerts(AlertSuccess::TYPE)));
	}

	public function testGetAlerts() {
		new AlertError('My error msg 1', 'group');
		new AlertError('My error msg 2', '');
		new AlertError('My error msg 3', 'other');
		new AlertSuccess('My success msg', 'group');

		/* All */
		$this->assertEquals(4, count(Session::getAlerts()));

		/* by Type */
		$this->assertEquals(0, count(Session::getAlerts(AlertDefault::TYPE)));
		$this->assertEquals(3, count(Session::getAlerts(AlertError::TYPE)));
		$this->assertEquals(1, count(Session::getAlerts(AlertSuccess::TYPE)));

		/* by Group */
		$this->assertEquals(2, count(Session::getAlerts('', 'group')));
		$this->assertEquals(1, count(Session::getAlerts('', 'other')));

		/* by Type and Group */
		$this->assertEquals(1, count(Session::getAlerts(AlertError::TYPE, 'other')));
		$this->assertEquals(0, count(Session::getAlerts(AlertSuccess::TYPE, 'other')));
	}

}
