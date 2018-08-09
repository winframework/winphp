<?php

namespace Win\Alert;

use Win\Alert\AlertInfo;

class AlertInfoTest extends \PHPUnit_Framework_TestCase {

	public function testGetType() {
		$instance = new AlertInfo('My Info msg');
		$this->assertEquals('info', $instance->type);
	}

}
