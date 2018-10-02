<?php

namespace Win\Html\Form;

use PHPUnit_Framework_TestCase;

class InputTextTest extends PHPUnit_Framework_TestCase {

	public function testGetValue_Empty() {
		$input = new InputText('music_name');
		$this->assertEquals('', $input->getValue());
	}

	public function testGetValue() {
		$input = new InputText('music_name', 'See you again', ['class' => 'form-control']);
		$this->assertEquals('See you again', $input->getValue());
	}

		public function testHtml() {
		$input = new InputText('music_name', 'See you again', ['class' => 'form-control']);
		$this->assertEquals('<input type="text" name="music_name" value="See you again" class="form-control" />', $input->html());
	}

}
