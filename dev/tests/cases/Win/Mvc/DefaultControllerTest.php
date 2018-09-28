<?php

namespace Win\Mvc;

class DefaultControllerTest extends \PHPUnit_Framework_TestCase {

	public function testLoad() {
		$app = new Application();
		$app->view = new View('index');
		$controller = new DefaultController('index');
		$controller->load();
		$this->assertTrue($controller instanceof DefaultController);
	}

	public function testCall() {
		new Application();
		$controller = ControllerFactory::create('inexist', 'ops');
		$this->assertEquals(true, $controller->thisActionDoentExist());
	}

}
