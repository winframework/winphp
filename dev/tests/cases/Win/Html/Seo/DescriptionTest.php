<?php

namespace Win\Html\Seo;

class DescriptionTest extends \PHPUnit_Framework_TestCase {

	public function testOtimizeDefault() {
		Description::$DEFAULT = 'My Default';
		$description = Description::otimize('');
		$this->assertEquals($description, 'My Default');
	}

	public function testOtimize() {
		Description::$DEFAULT = 'My Default';
		$description = Description::otimize('My Short Description');
		$this->assertEquals($description, 'My Short Description');

		Description::$MAX_LENGTH = 20;
		$description2 = Description::otimize('My Long Description of Test of Many characters');
		$this->assertEquals($description2, 'My Long Description...');


		Description::$MAX_LENGTH = 15;
		$description3 = Description::otimize('My Long Description of Test of Many characters');
		$this->assertEquals($description3, 'My Long...');
	}

}
