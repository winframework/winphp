<?php

namespace Win\Calendar;

class MonthTest extends \PHPUnit_Framework_TestCase {

	public function testGetName() {
		$this->assertEquals(Month::getName(1), 'Janeiro');
		$this->assertEquals(Month::getName(11), 'Novembro');
		$this->assertNotEquals(Month::getName(02), 'Novembro');
		$this->assertFalse(Month::getName(13));
		$this->assertFalse(Month::getName(0));
		$this->assertFalse(Month::getName(-2));
	}
public function testGetNameAbree() {
		$this->assertEquals(Month::getNameAbbre(1), 'JAN');
		$this->assertEquals(Month::getNameAbbre(11), 'NOV');
		$this->assertNotEquals(Month::getNameAbbre(02), 'NOV');
		$this->assertFalse(Month::getNameAbbre(13));
	}
}
