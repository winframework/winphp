<?php

namespace Win\Html\Form;

class SelectTest extends \PHPUnit_Framework_TestCase {

	private static $selected = 'selected';

	public function testSelected() {
		$this->assertContains(static::$selected, Select::selected(true));
		$this->assertContains(static::$selected, Select::selected(1));
		$this->assertContains(static::$selected, Select::selected('teste', 'teste'));
	}

	public function testNotSelected() {
		$this->assertNotContains(static::$selected, Select::selected(false));
		$this->assertNotContains(static::$selected, Select::selected(0));
		$this->assertNotContains(static::$selected, Select::selected('teste', 'wrong'));
	}

	public function testToString_Selected() {
		$select = new Select(['Abacaxi', 'Maça', 'Tomate'], 'Maça');
		$this->assertContains(static::$selected, (string) $select);
	}

	public function testToString_NotSelected() {
		$select = new Select(['Abacaxi', 'Maça', 'Tomate']);
		$this->assertNotContains(static::$selected, (string) $select);
	}

	public function testCountOptions() {
		$select0 = new Select([], 'Tomate');
		$this->assertEquals($this->countOptions($select0), 0);

		$select1 = new Select(['Abacaxi'], 'Tomate');
		$this->assertEquals($this->countOptions($select1), 1);

		$select3 = new Select(['Abacaxi', 'Maça', 'Tomate'], 'Tomate');
		$this->assertEquals($this->countOptions($select3), 3);
	}

	private function countOptions($select) {
		return substr_count((string) $select, '<option');
	}

}
