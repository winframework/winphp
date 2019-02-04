<?php

namespace Win\Mvc;

use PHPUnit\Framework\TestCase;
use Win\Mvc\View;
use Win\Request\Url;
use Win\Html\Seo\Title;

class ViewTest extends TestCase {

	public function getValidView() {
		return new View('my-view');
	}

	public function getInvalidView() {
		return new View('this-view-doesnt-exist');
	}

	/**
	 * @expectedException \Win\Mvc\HttpException
	 */
	public function testDoNotExist() {
		$view = $this->getInValidView();
		$this->assertFalse($view->exists());
		$view->validate();
	}

	public function testExists() {
		$view = $this->getValidView();
		$this->assertTrue($view->exists());
		$this->assertFalse(empty($view->toString()));
		$view->validate();
	}

	public function testGetFile() {
		$view = $this->getValidView();
		$this->assertContains('my-view.phtml', $view->getFile());
		$view2 = $this->getInvalidView();
		$this->assertContains('this-view-doesnt-exist.phtml', $view2->getFile());
	}

	public function testToString() {
		$viewEmpty = $this->getInValidView();
		$this->assertTrue(empty($viewEmpty->toString()));

		$viewNotEmpty = $this->getValidView();
		$this->assertFalse(empty($viewNotEmpty->toString()));
	}

	public function testToHtml() {
		$view = $this->getValidView();
		ob_start();
		$view->toHtml();
		$this->assertContains('My custom', ob_get_clean());
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

	public function testParamMergeAndAddData() {
		$view = new View('index', ['a' => 5, 'c' => 30]);
		$view->mergeData(['a' => 15, 'b' => 20]);
		$view->addData('a', 10);

		$this->assertEquals($view->getData('a'), 10);
		$this->assertEquals($view->getData('b'), 20);
		$this->assertEquals($view->getData('c'), 30);
	}

	public function testGetTitle_Manual() {
		$view = new View('index', ['title' => 'My title']);
		$this->assertEquals('My title', $view->getTitle());
	}

	public function testGetTitle_Automatic() {
		Url::instance()->setUrl('my-page/my-subpage');
		new Application();
		$view = new View('index');
		Title::$prefix = '';
		Title::$suffix = '';
		$this->assertEquals('My Page', $view->getTitle());
	}

}
