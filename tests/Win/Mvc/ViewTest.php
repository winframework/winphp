<?php

namespace Win\Mvc;

use Win\Mvc\View;

class ViewTest extends \PHPUnit_Framework_TestCase {

	public function testViewNotFound() {
		$view = new View('this-view-doesnt-exist');
		$this->assertFalse($view->exists());
	}

	public function testViewIndex() {
		$view = new View('index');
		$this->assertTrue($view->exists());
	}

	public function testToString() {
		$viewEmpty = new View('this-file-doent-exist');
		$this->assertEquals(0, strlen($viewEmpty->toString()));

		$viewNotEmpty = new View('index');
		$this->assertNotEquals(0, strlen($viewNotEmpty->toString()));
	}

	

}
