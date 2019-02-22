<?php

namespace Win\Mvc;

use PHPUnit\Framework\TestCase;

class DefaultControllerTest extends TestCase {

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

	public function testGetAction() {
			$controller = ControllerFactory::create('inexist', 'ops');
		$this->assertEquals('index', $controller->getAction());
	}

}
