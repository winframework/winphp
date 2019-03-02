<?php

namespace Win\Html\Form;

use PHPUnit\Framework\TestCase;

class InputTextTest extends TestCase
{
	public function testGetValue()
	{
		$input = new InputText('music_name');
		$this->assertEquals('', $input->getValue());

		$input = new InputText(
			'music_name',
			'See you again',
			['class' => 'form-control']
		);
		$this->assertEquals('See you again', $input->getValue());
	}

	public function testHtml()
	{
		$input = new InputText(
			'music_name',
			'See you again',
			['class' => 'form-control']
		);
		$this->assertEquals('<input type="text" name="music_name" value="See you again" class="form-control" />', $input->html());
	}
}
