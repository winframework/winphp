<?php

namespace Win\Calendar;

use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
	private $dateSQL = '1991-09-07 12:15:01';
	private $dateUS = '09-07-1991 12:15:01';
	private $dateBR = '07/09/1991 12:15:01';
	private $dateZERO = '00/00/0000 00:00:01';

	public function testConstructDefault()
	{
		$date = new DateTime($this->dateSQL);
		$this->assertDate($date);
	}

	public function testConstructSQLDateTime()
	{
		$date = DateTime::create(DateTime::SQL_DATE_TIME, $this->dateSQL);
		$this->assertDate($date);
	}

	public function testConstructBRDateTime()
	{
		$date = DateTime::create(DateTime::BR_DATE_TIME, $this->dateBR);
		$this->assertDate($date);
	}

	public function testConstructBRDate()
	{
		$date = DateTime::create(DateTime::BR_DATE, '07/09/1991');
		$this->assertDate($date);
	}

	public function testConstructSQLDate()
	{
		$date = DateTime::create(DateTime::SQL_DATE, '1991-09-07');
		$this->assertDate($date);
	}

	public function testConstructUS()
	{
		$date = DateTime::create('m-d-Y H:i:s', $this->dateUS);
		$this->assertDate($date);
	}

	public function testIsEmpty()
	{
		$date = DateTime::create(DateTime::BR_DATE_TIME, $this->dateZERO);
		$this->assertTrue($date->isEmpty());
	}

	public function testIsNotEmpty()
	{
		$date = DateTime::create(DateTime::BR_DATE_TIME, $this->dateBR);
		$this->assertFalse($date->isEmpty());
	}

	public function testGetAge()
	{
		$date = DateTime::create(DateTime::BR_DATE_TIME, $this->dateBR);
		$beginYear = DateTime::create(DateTime::BR_DATE_TIME, '01/01/2000 00:00:01');
		$endYear = DateTime::create(DateTime::BR_DATE_TIME, '25/11/2000 00:00:01');

		$this->assertEquals(8, $date->getAge($beginYear));
		$this->assertEquals(9, $date->getAge($endYear));
	}

	public function testGetMonthName()
	{
		$date1 = DateTime::create(DateTime::BR_DATE_TIME, $this->dateBR);
		$date2 = DateTime::create(DateTime::BR_DATE_TIME, '25/11/2000 00:00:01');
		$this->assertEquals('setembro', $date1->getMonthName());
		$this->assertEquals('novembro', $date2->getMonthName());
	}

	public function testGetMonthShortName()
	{
		$date1 = DateTime::create(DateTime::BR_DATE_TIME, $this->dateBR);
		$date2 = DateTime::create(DateTime::BR_DATE_TIME, '25/11/2000 00:00:01');
		$this->assertEquals('set', $date1->getMonthShortName());
		$this->assertEquals('nov', $date2->getMonthShortName());
	}

	public function testToHtml()
	{
		$date1 = DateTime::create(DateTime::BR_DATE_TIME, $this->dateBR);
		$this->assertEquals($this->dateBR, $date1->toHtml());
	}

	public function testToString()
	{
		$date1 = DateTime::create(DateTime::BR_DATE_TIME, $this->dateBR);
		$this->assertEquals('07/09/1991', (string) $date1);
	}

	public function testToSQL()
	{
		$date1 = DateTime::create(DateTime::BR_DATE_TIME, $this->dateBR);
		$this->assertEquals($this->dateSQL, $date1->toSql());
	}

	public function testIsValid()
	{
		$sqlFormat = DateTime::SQL_DATE_TIME;
		$this->assertTrue(DateTime::isValid($this->dateBR));
		$this->assertTrue(DateTime::isValid($this->dateSQL, $sqlFormat));
		$this->assertFalse(DateTime::isValid('25/01/1940 AA:01:01'));
		$this->assertFalse(DateTime::isValid('25/01/1940', $sqlFormat));
	}

	public function testLimitTime()
	{
		$date1 = DateTime::create(DateTime::BR_DATE_TIME, '01/01/1980 23:59:61');
		$this->assertEquals(01, $date1->format('s'));
		$this->assertEquals(00, $date1->format('H'));
		$this->assertEquals(02, $date1->format('d'));
	}

	public function testLimitDays()
	{
		$date1 = DateTime::create(DateTime::BR_DATE, '32/01/1980');
		$this->assertEquals(01, $date1->format('d'));
		$this->assertEquals(02, $date1->format('m'));
	}

	public function testLimitMonth()
	{
		$date1 = DateTime::create(DateTime::BR_DATE, '01/13/1980');
		$this->assertEquals(01, $date1->format('m'));
		$this->assertEquals(1981, $date1->format('Y'));
	}

	public function testCreateEmpty()
	{
		$date1 = DateTime::createEmpty();
		$this->assertTrue($date1->isEmpty());
	}

	private function assertDate($date)
	{
		$this->assertEquals($date->format('d'), 7);
		$this->assertEquals($date->format('m'), 9);
		$this->assertEquals($date->format('y'), 91);
		$this->assertEquals($date->format('Y'), 1991);
	}
}
