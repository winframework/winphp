<?php

namespace Win\Html\Form;

class SelectTest extends \PHPUnit_Framework_TestCase {

	private static $active = 'selected';

	public function testIsActive() {
		$this->assertContains(static::$active, Select::active(true));
		$this->assertContains(static::$active, Select::active(1));
		$this->assertContains(static::$active, Select::active('teste', 'teste'));
	}

	public function testIsNotActive() {
		$this->assertNotContains(static::$active, Select::active(false));
		$this->assertNotContains(static::$active, Select::active(0));
		$this->assertNotContains(static::$active, Select::active('teste', 'wrong'));
	}

	public function testToString() {
		$options = ['Abacaxi', 'Maça', 'Tomate'];
		$select = new Select($options, 'Tomate');
		$this->assertTrue($this->hasSelected($select));

		$select2 = new Select($options, 'Uva');
		$this->assertNotTrue($this->hasSelected($select2));

		$select3 = new Select($options);
		$this->assertNotTrue($this->hasSelected($select3));
	}

	public function testCountOptions() {
		$select0 = new Select([], 'Tomate');
		$this->assertEquals($this->countOptions($select0), 0);

		$select1 = new Select(['Abacaxi'], 'Tomate');
		$this->assertEquals($this->countOptions($select1), 1);

		$select3 = new Select(['Abacaxi', 'Maça', 'Tomate'], 'Tomate');
		$this->assertEquals($this->countOptions($select3), 3);
	}

	private function hasSelected($select) {
		return (strpos((string) $select, 'selected') > 0);
	}

	private function countOptions($select) {
		return substr_count((string) $select, '<option');
	}

}
