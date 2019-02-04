<?php

namespace Win\Calendar;

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

class DateTimeTest extends \PHPUnit\Framework\TestCase {

	private $dateSQL = '1991-09-07 12:15:01';
	private $dateUS = '09-07-1991 12:15:01';
	private $dateBR = '07/09/1991 12:15:01';
	private $dateZERO = '00/00/0000 00:00:01';

	public function testConstructDefault() {
		$date = new DateTime($this->dateSQL);
		$this->assertDate($date);
	}

	public function testConstructSQLDateTime() {
		$date = DateTime::create(DateTime::SQL_DATE_TIME, $this->dateSQL);
		$this->assertDate($date);
	}

	public function testConstructBRDateTime() {
		$date = DateTime::create(DateTime::BR_DATE_TIME, $this->dateBR);
		$this->assertDate($date);
	}

	public function testConstructBRDate() {
		$date = DateTime::create(DateTime::BR_DATE, '07/09/1991');
		$this->assertDate($date);
	}

	public function testConstructSQLDate() {
		$date = DateTime::create(DateTime::SQL_DATE, '1991-09-07');
		$this->assertDate($date);
	}

	public function testConstructUS() {
		$date = DateTime::create('m-d-Y H:i:s', $this->dateUS);
		$this->assertDate($date);
	}

	public function testIsEmpty() {
		$date = DateTime::create(DateTime::BR_DATE_TIME, $this->dateZERO);
		$this->assertTrue($date->isEmpty());
	}

	public function testIsNotEmpty() {
		$date = DateTime::create(DateTime::BR_DATE_TIME, $this->dateBR);
		$this->assertFalse($date->isEmpty());
	}

	public function testGetAge() {
		$date = DateTime::create(DateTime::BR_DATE_TIME, $this->dateBR);
		$beginYear = DateTime::create(DateTime::BR_DATE_TIME, '01/01/2000 00:00:01');
		$endYear = DateTime::create(DateTime::BR_DATE_TIME, '25/11/2000 00:00:01');

		$this->assertEquals($date->getAge($beginYear), 8);
		$this->assertEquals($date->getAge($endYear), 9);
	}

	public function testGetMonthName() {
		$date1 = DateTime::create(DateTime::BR_DATE_TIME, $this->dateBR);
		$date2 = DateTime::create(DateTime::BR_DATE_TIME, '25/11/2000 00:00:01');
		$this->assertEquals($date1->getMonthName(), 'setembro');
		$this->assertEquals($date2->getMonthName(), 'novembro');
	}

	public function testGetMonthShortName() {
		$date1 = DateTime::create(DateTime::BR_DATE_TIME, $this->dateBR);
		$date2 = DateTime::create(DateTime::BR_DATE_TIME, '25/11/2000 00:00:01');
		$this->assertEquals($date1->getMonthShortName(), 'set');
		$this->assertEquals($date2->getMonthShortName(), 'nov');
	}

	public function testToHtml() {
		$date1 = DateTime::create(DateTime::BR_DATE_TIME, $this->dateBR);
		$this->assertEquals($date1->toHtml(), $this->dateBR);
	}

	public function testToString() {
		$date1 = DateTime::create(DateTime::BR_DATE_TIME, $this->dateBR);
		$this->assertEquals((string) $date1, '07/09/1991');
	}

	public function testToSQL() {
		$date1 = DateTime::create(DateTime::BR_DATE_TIME, $this->dateBR);
		$this->assertEquals($date1->toSql(), $this->dateSQL);
	}

	public function testIsValid() {
		$this->assertTrue(DateTime::isValid($this->dateBR));
		$this->assertTrue(DateTime::isValid($this->dateSQL, DateTime::SQL_DATE_TIME));
	}

	public function testIsNotValid() {
		$this->assertFalse(DateTime::isValid('25/01/1940 AA:01:01'));
	}

	public function testWrongFormat() {
		$this->assertFalse(DateTime::isValid('25/01/1940', DateTime::SQL_DATE_TIME));
	}

	public function testLimitTime() {
		$date1 = DateTime::create(DateTime::BR_DATE_TIME, '01/01/1980 23:59:61');
		$this->assertEquals($date1->format('s'), 01);
		$this->assertEquals($date1->format('H'), 00);
		$this->assertEquals($date1->format('d'), 02);
	}

	public function testLimitDays() {
		$date1 = DateTime::create(DateTime::BR_DATE, '32/01/1980');
		$this->assertEquals($date1->format('d'), 01);
		$this->assertEquals($date1->format('m'), 02);
	}

	public function testLimitMonth() {
		$date1 = DateTime::create(DateTime::BR_DATE, '01/13/1980');
		$this->assertEquals($date1->format('m'), 01);
		$this->assertEquals($date1->format('Y'), 1981);
	}

	public function testCreateEmpty() {
		$date1 = DateTime::createEmpty();
		$this->assertTrue($date1->isEmpty());
	}

	private function assertDate($date) {
		$this->assertEquals($date->format('d'), 7);
		$this->assertEquals($date->format('m'), 9);
		$this->assertEquals($date->format('y'), 91);
		$this->assertEquals($date->format('Y'), 1991);
	}

}
