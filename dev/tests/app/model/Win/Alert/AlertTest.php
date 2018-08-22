<?php

namespace Win\Alert;

use Win\Alert\AlertError;

class AlertTest extends \PHPUnit_Framework_TestCase {

	public function testGetMessage() {
		$instance = new AlertError('My Alert');
		$this->assertEquals('My Alert', $instance->message);
	}

	public function testToString() {
		$instance = new AlertError('My Alert Text');
		$this->assertContains('My Alert Text', (string) $instance);
	}

	public function testGetType() {
		$instance = new AlertError('My Error msg');
		$this->assertEquals('danger', $instance->type);

		$instance2 = new AlertSuccess('My Success msg');
		$this->assertEquals('success', $instance2->type);
	}

	public function testLoad() {
		$alert = new AlertError('My Error msg');
		ob_start();
		$alert->load();
		$this->assertNotEmpty(ob_get_clean());
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

	public function testIsType() {
		$msg = new AlertDefault('Test');
		$this->assertTrue($msg->isType(''));
		$this->assertTrue($msg->isType(AlertDefault::TYPE));
		$this->assertFalse($msg->isType('other-type'));
	}

	public function testIsGroup() {
		$msg = new AlertDefault('Test', 'my-group');
		$this->assertTrue($msg->isGroup(''));
		$this->assertTrue($msg->isGroup('my-group'));
		$this->assertFalse($msg->isGroup('other-group'));
	}

	public function testS() {
		new AlertSuccess('Falha ao conectar ao servidor FTP.', 'ftp');
		$this->assertEquals(1, count(Session::getAlerts('', 'ftp')));
	}

}
