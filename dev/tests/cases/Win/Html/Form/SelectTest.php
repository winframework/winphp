<?php

namespace Win\Html\Form;

use PHPUnit_Framework_TestCase;

class SelectTest extends PHPUnit_Framework_TestCase {

	/** @var string */
	private static $options = ['S', 'M', 'B'];

	/** @var string[] */
	private static $optionsAssoc = ['s' => 'small', 'm' => 'medium', 'b' => 'big'];

	public function testSelected() {
		$this->assertContains('selected', Select::select(true));
		$this->assertContains('selected', Select::select(1));
		$this->assertContains('selected', Select::select('teste', 'teste'));
	}

	public function testConstruct() {
		$select = new Select('size', static::$options);
		$this->assertInstanceOf(Select::class, $select);
	}

	public function testConstruct_Attributes() {
		$attr = ['class' => 'form-control', 'id' => 'size_select'];
		$select = new Select('size', static::$options, 'm', $attr);
		$this->assertInstanceOf(Select::class, $select);
	}

	public function testGetValue_Key() {
		$select = new Select('size', static::$options, 1);
		$this->assertEquals('M', $select->getValue());
	}

	public function testGetValue_Value() {
		$select = new Select('size', static::$options, 'M');
		$this->assertEquals('M', $select->getValue());
	}

	public function testGetValue_Invalid() {
		$select = new Select('size', static::$options, 'XG');
		$this->assertEquals(null, $select->getValue());
	}

	public function testGetValue_Assoc() {
		$select = new Select('size', static::$optionsAssoc, 'm');
		$this->assertEquals('medium', $select->getValue());
	}

	public function testHtmlContent() {
		$select = new Select('size', static::$options, 1);
		$this->assertContains('<option selected value="1"', $select->htmlContent());
	}

	public function testHtmlContent_Value() {
		$select = new Select('size', static::$options, 'M');
		$this->assertContains('<option selected value="1"', $select->htmlContent());
	}

	public function testHtmlContent_Assoc() {
		$select = new Select('size', static::$optionsAssoc, 'm');
		$this->assertContains('<option selected value="m"', $select->htmlContent());
	}

	public function testHtml() {
		$attr = ['id' => 'size_select', 'class' => 'form-control'];
		$select = new Select('size', static::$optionsAssoc, 'm', $attr);
		$this->assertContains('<select name="size" id="size_select" class="form-control" >', $select->html());
		$this->assertContains('<option selected value="m"', $select->html());
		$this->assertEquals($select->html(), (string) $select);
	}

	public function testHtml_OptionGroup() {

		$select = new Select('animal', [
			'Cats' => ['leopard' => 'Leopard'],
			'Dogs' => ['spaniel' => 'Spaniel', 'luke' => 'Luke']
				], 'spaniel');
		$this->assertContains('<optgroup label="Cats"> <option', $select->html());
		$this->assertContains('<option selected value="spaniel">Spaniel', $select->html());
	}

}
