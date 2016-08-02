<?php

namespace Win\Mvc;

use Win\Mvc\ViewFactory;
use Win\Helper\Url;

class ViewFactoryTest extends \PHPUnit_Framework_TestCase {

	public function testCreateView() {
		$defaultView = ViewFactory::create('this-page-doent-exist');
		$this->assertFalse($defaultView->exists());

		$indexView = ViewFactory::create('index');
		$this->assertTrue($indexView->exists());

		$urlFragments = ['index'];
		$otherView = ViewFactory::create('this-page-doent-exist', $urlFragments);
		$this->assertTrue($otherView->exists());

		$urlFragments2 = ['index', 'this-subpage-doent-exit'];
		$otherView2 = ViewFactory::create('this-page-doent-exist', $urlFragments2);
		$this->assertFalse($otherView2->exists());
	}

	public function testCreateViewWithParams() {
		$view = ViewFactory::create('this-page-doent-exist', ['index']);
		$this->assertTrue($view->exists());

		$viewInexistent = ViewFactory::create('this-page-doent-exist', ['page-doent-exist']);
		$this->assertFalse($viewInexistent->exists());
	}

}
