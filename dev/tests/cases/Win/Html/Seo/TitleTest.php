<?php

namespace Win\Html\Seo;

use Win\Mvc\Application;

class TitleTest extends \PHPUnit_Framework_TestCase {

	public function testOtimize() {
		Title::$MAX_LENGTH = 100;
		$this->assertEquals(Title::otimize('My custom Title'), 'My custom Title');
		Title::$MAX_LENGTH = 10;
		$this->assertEquals(Title::otimize('My custom Title'), 'My...');
	}

	public function testOtimizePrefix() {
		Title::$prefix = '|| ';
		Title::$sufix = '';
		Title::$MAX_LENGTH = 100;
		$this->assertEquals(Title::otimize('My custom Title'), '|| My custom Title');
		Title::$MAX_LENGTH = 10;
		$this->assertEquals(Title::otimize('My custom Title'), '|| My...');
	}

	public function testOtimizeSufix() {
		Title::$prefix = '';
		Title::$sufix = ' ||';
		Title::$MAX_LENGTH = 100;
		$this->assertEquals(Title::otimize('My custom Title'), 'My custom Title ||');
		Title::$MAX_LENGTH = 10;
		$this->assertEquals(Title::otimize('My custom Title'), 'My... ||');
	}

	public function testOtimizeSufixAndPrefix() {
		Title::$prefix = '|| ';
		Title::$sufix = ' ||';
		Title::$MAX_LENGTH = 100;
		$this->assertEquals(Title::otimize('My custom Title'), '|| My custom Title ||');
		Title::$MAX_LENGTH = 15;
		$this->assertEquals(Title::otimize('My custom Title'), '|| My... ||');
		Title::$MAX_LENGTH = 10;
		$this->assertEquals(Title::otimize('My custom Title'), '|| ... ||');
	}

	public function testSetTitle() {
		$app = new Application();
		$app->controller->setTitle('My old page Title');
		Title::$prefix = '.:: ';
		Title::$sufix = ' ::.';
		Title::$MAX_LENGTH = 25;
		Title::setTitle('My custom and longest Title');
		$title = $app->controller->getData('title');

		$this->assertEquals($title, '.:: My custom and... ::.');
	}

}
