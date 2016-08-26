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

	public function testParamData() {
		$view = new View('index', ['a' => 5]);
		$this->assertEquals($view->getData('a'), 5);
	}

	public function testAddData() {
		$view = new View('index');
		$view->addData('a', 10);
		$this->assertEquals($view->getData('a'), 10);
	}

	public function testMergeData() {
		$view = new View('index');
		$view->mergeData(['a' => 10, 'b' => 20]);

		$this->assertEquals($view->getData('a'), 10);
		$this->assertEquals($view->getData('b'), 20);
	}

	public function testAddDataAndMergeData() {
		$view = new View('index');
		$view->addData('a', 10);
		$view->mergeData(['a' => 15, 'b' => 20]);

		$this->assertEquals($view->getData('a'), 15);
		$this->assertEquals($view->getData('b'), 20);
	}

	public function testParamAndMergeDataAndAddData() {
		$view = new View('index', ['a' => 5, 'c' => 30]);
		$view->mergeData(['a' => 15, 'b' => 20]);
		$view->addData('a', 10);

		$this->assertEquals($view->getData('a'), 10);
		$this->assertEquals($view->getData('b'), 20);
		$this->assertEquals($view->getData('c'), 30);
	}

}
