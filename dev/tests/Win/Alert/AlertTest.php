<?php

namespace Win\Alert;

use Win\Alert\AlertError;

class AlertTest extends \PHPUnit_Framework_TestCase {

	public function testGetMessage() {
		$instance = new AlertError('My Alert');
		$this->assertEquals('My Alert', $instance->message);
		$this->assertEquals('My Alert', $instance);
	}

	public function testGetType() {
		$instance = new AlertError('My Error msg');
		$this->assertEquals('danger', $instance->type);

		$instance2 = new AlertSuccess('My Success msg');
		$this->assertEquals('success', $instance2->type);
	}

	public function testLoad() {
		ob_start();
		$alert = new AlertError('My Error msg');
		$alert->load();
		$content = ob_get_contents();
		ob_end_clean();
		$this->assertNotEmpty($content);
	}

	public function testCreateAlertWithError() {
		ob_start();
		Session::showAlerts();
		$alert = Alert::create('This is a error', 'Congratulations');
		$this->assertTrue($alert instanceof AlertError);
		$this->assertEquals('This is a error', $alert->message);
		ob_end_clean();
	}

	public function testCreateNoError() {
		ob_start();
		Session::showAlerts();
		$alert = Alert::create(null, 'Congratulations');
		$this->assertTrue($alert instanceof AlertSuccess);
		$this->assertEquals('Congratulations', $alert->message);
		ob_end_clean();
	}

}
