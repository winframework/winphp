<?php

namespace Win\Alert;

use Win\Alert\AlertDefault;

class AlertDefaultTest extends \PHPUnit_Framework_TestCase {

	public function testGetType() {
		$instance = new AlertDefault('My Default msg');
		$this->assertEquals('default', $instance->type);
	}

}
