<?php

namespace Win\Html\Seo;

use Win\Mvc\Application;

class TitleTest extends \PHPUnit\Framework\TestCase {

	public function testOtimize() {
		$this->assertEquals('My custom Title', Title::otimize('My custom Title', 100));
		$this->assertEquals('My custom...', Title::otimize('My custom Title', 11));
	}

	public function testOtimizePrefix() {
		Title::$prefix = '|| ';
		Title::$sufix = '';
		$this->assertEquals('|| My custom Title', Title::otimize('My custom Title', 100));
		$this->assertEquals('|| My...', Title::otimize('My custom Title', 11));
	}

	public function testOtimizeSufix() {
		Title::$prefix = '';
		Title::$sufix = ' ||';
		$this->assertEquals('My custom Title ||', Title::otimize('My custom Title', 100));
		$this->assertEquals('My... ||', Title::otimize('My custom Title', 11));
	}

	public function testOtimizeSufixAndPrefix() {
		Title::$prefix = '|| ';
		Title::$sufix = ' ||';
		$this->assertEquals('|| My custom Title ||', Title::otimize('My custom Title', 100));
		$this->assertEquals('|| My... ||', Title::otimize('My custom Title', 13));
		$this->assertEquals('|| ... ||', Title::otimize('My custom Title', 7));
	}

	public function testSetTitle() {
		$app = new Application();
		$app->controller->setTitle('My old page Title');
		Title::$prefix = '.:: ';
		Title::$sufix = ' ::.';
		Title::setTitle('My custom and longest Title and longest Title and longest Title and longest Title and longest Title and longest Title');
		$title = $app->controller->getData('title');

		$this->assertEquals('.:: My custom and longest Title and longest Title and longest... ::.', $title);
	}

}
