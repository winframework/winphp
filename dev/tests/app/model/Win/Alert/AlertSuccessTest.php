<?php

namespace Win\Alert;

use Win\Alert\AlertSuccess;

class AlertSuccessTest extends \PHPUnit_Framework_TestCase {

	public function testGetType() {
		$instance = new AlertSuccess('My Success msg');
		$this->assertEquals(AlertSuccess::TYPE, $instance->type);
	}

}
