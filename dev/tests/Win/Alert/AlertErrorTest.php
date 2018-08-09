<?php

namespace Win\Alert;

use Win\Alert\AlertError;

class AlertErrorTest extends \PHPUnit_Framework_TestCase {

	public function testGetType() {
		$instance = new AlertError('My Error msg');
		$this->assertEquals('danger', $instance->type);
	}

}
