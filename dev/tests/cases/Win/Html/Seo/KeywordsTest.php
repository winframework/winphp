<?php

namespace Win\Html\Seo;

class KeywordsTest extends \PHPUnit_Framework_TestCase {

	public function testToKeys() {
		$this->loadController();
		$keys = Keywords::toKeys(['first', 'Second', 'THIRD']);
		$this->assertEquals($keys, 'first, second, third');

		Keywords::$MAX_LENGTH = 10;
		$keys2 = Keywords::toKeys(['first', 'SE-cond', 'third']);
		$this->assertEquals($keys2, 'first, se-cond');

		Keywords::$MAX_LENGTH = 100;
		$keys4 = Keywords::toKeys(['first, second'], ['tHird', 'Fourth']);
		$this->assertEquals($keys4, 'first, second, third, fourth');


		Keywords::$MAX_LENGTH = 25;
		$keys5 = Keywords::toKeys(['first, second', 'Third'], ['fourth', 'fifth']);
		$this->assertEquals($keys5, 'first, second, third, fourth');
	}

	public function testToKeysController() {
		$this->loadController();
		\Win\Mvc\Application::app()->controller->addData('keywords', 'my custom, keywords');
		Keywords::$MAX_LENGTH = 100;
		$keys = Keywords::toKeys(['first', 'second', 'third']);
		$this->assertEquals($keys, 'first, second, third, my custom, keywords');

		Keywords::$MAX_LENGTH = 30;
		$keys2 = Keywords::toKeys(['first', 'SECOND', 'third']);
		$this->assertEquals($keys2, 'first, second, third, my custom');

		Keywords::$MAX_LENGTH = 10;
		$keys3 = Keywords::toKeys(['first', 'second', 'third']);
		$this->assertEquals($keys3, 'first, second');
	}

	public function loadController() {
		$app = new \Win\Mvc\Application();
		$app->controller->load();
	}

}
