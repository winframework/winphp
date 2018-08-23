<?php

namespace Win\Calendar;

class MonthTest extends \PHPUnit_Framework_TestCase {

	public function testConstruct() {
		$m1 = new Month(1);
		$this->assertEquals($m1->getName(), 'Janeiro');
		$m9 = new Month(9);
		$this->assertEquals($m9->getName(), 'Setembro');
	}

	public function testCreate() {
		$m3 = new Month(3);
		$this->assertEquals($m3->getName(), Month::create(3)->getName());
	}

	public function testGetName() {
		$this->assertEquals(Month::create(1)->getName(), 'Janeiro');
		$this->assertEquals(Month::create(11)->getName(), 'Novembro');
		$this->assertNotEquals(Month::create(02)->getName(), 'Novembro');
		$this->assertFalse(Month::create(13)->getName());
		$this->assertFalse(Month::create(0)->getName());
		$this->assertFalse(Month::create(-2)->getName());
	}

	public function testGetNameAbree() {
		$this->assertEquals(Month::create(1)->getNameAbbre(), 'JAN');
		$this->assertEquals(Month::create(11)->getNameAbbre(), 'NOV');
		$this->assertNotEquals(Month::create(02)->getNameAbbre(), 'NOV');
		$this->assertFalse(Month::create(13)->getNameAbbre());
	}

}
