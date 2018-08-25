<?php

namespace Win\Mvc;

class DefaultControllerTest extends \PHPUnit_Framework_TestCase {

	public function testCreate() {
		new Application();
		$controller = ControllerFactory::create('inexist');
		$this->assertTrue($controller instanceof DefaultController);
	}

}
