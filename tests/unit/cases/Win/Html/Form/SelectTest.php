<?php

namespace Win\Html\Form;

use PHPUnit\Framework\TestCase;

class SelectTest extends TestCase
{
	/** @var string */
	const OPTIONS = ['S', 'M', 'B'];

	/** @var string[] */
	const OPTIONS_ASSOC = ['s' => 'small', 'm' => 'medium', 'b' => 'big'];

	public function testSelected()
	{
		$this->assertContains('selected', Select::select(true));
		$this->assertContains('selected', Select::select(1));
		$this->assertContains('selected', Select::select('teste', 'teste'));
	}

	public function testConstruct()
	{
		$select = new Select('size', static::OPTIONS);
		$this->assertInstanceOf(Select::class, $select);
	}

	public function testConstructAttributes()
	{
		$attr = ['class' => 'form-control', 'id' => 'size_select'];
		$select = new Select('size', static::OPTIONS, 'm', $attr);
		$this->assertInstanceOf(Select::class, $select);
	}

	public function testGetValue()
	{
		$select = new Select('size', static::OPTIONS, 1);
		$this->assertEquals('M', $select->getValue());
		$select = new Select('size', static::OPTIONS, 'M');
		$this->assertEquals('M', $select->getValue());
		$select = new Select('size', static::OPTIONS, 'XG');
		$this->assertEquals(null, $select->getValue());
		$select = new Select('size', static::OPTIONS_ASSOC, 'm');
		$this->assertEquals('medium', $select->getValue());
	}

	public function testHtmlContent()
	{
		$select = new Select('size', static::OPTIONS, 1);
		$this->assertContains('<option selected value="1"', $select->htmlContent());
		$select = new Select('size', static::OPTIONS, 'M');
		$this->assertContains('<option selected value="1"', $select->htmlContent());
		$select = new Select('size', static::OPTIONS_ASSOC, 'm');
		$this->assertContains('<option selected value="m"', $select->htmlContent());
	}

	public function testHtml()
	{
		$attr = ['id' => 'size_select', 'class' => 'form-control'];
		$select = new Select('size', static::OPTIONS_ASSOC, 'm', $attr);
		$this->assertContains('<select name="size" id="size_select" class="form-control" >', $select->html());
		$this->assertContains('<option selected value="m"', $select->html());
		$this->assertEquals($select->html(), (string) $select);
	}

	public function testHtmlOptionGroup()
	{
		$select = new Select('animal', [
			'Cats' => ['leopard' => 'Leopard'],
			'Dogs' => ['spaniel' => 'Spaniel', 'luke' => 'Luke'],
		], 'spaniel');
		$this->assertContains('<optgroup label="Cats"> <option', $select->html());
		$this->assertContains('<option selected value="spaniel">Spaniel', $select->html());
	}
}
