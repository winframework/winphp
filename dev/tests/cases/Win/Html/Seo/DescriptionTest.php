<?php

namespace Win\Html\Seo;

class DescriptionTest extends \PHPUnit_Framework_TestCase {

	public function testOtimize_Default() {
		Description::$default = 'My Default';
		$this->assertEquals('My Default', Description::otimize(''));
	}

	public function testOtimize_NotDefault() {
		Description::$default = 'My Default';
		$this->assertEquals('My Short Description', Description::otimize('My Short Description'));
	}

	public function testOtimize_20() {
		$string = Description::otimize('My Long Description of Test of Many characters', 20);
		$this->assertEquals('My Long Description...', $string);
	}

	public function testOtimize_15() {
		$string = Description::otimize('My Long Description of Test of Many characters', 15);
		$this->assertEquals('My Long...', $string);
	}

}
