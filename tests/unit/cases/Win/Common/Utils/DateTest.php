<?php

namespace Win\Models;

use PHPUnit\Framework\TestCase;
use Win\Common\Utils\Date;

class DateTest extends TestCase
{

	public function testCreate()
	{
		$date = Date::create('1993-01-26 13:14:15', 'Y-m-d H:i:s', 'd/m/Y H:i:s');
		$this->assertEquals('26/01/1993 13:14:15', $date);

		$date = Date::create('26/01/93 13:14:15', 'd/m/y H:i:s');
		$this->assertEquals('1993-01-26 13:14:15', $date);

		$date = Date::create('1993-01-26', 'Y-m-d', 'd/m/y');
		$this->assertEquals('26/01/93', $date);
	}

	public function testFormat()
	{
		$date = Date::format('1993-01-26 13:14:15', 'd/m/Y H:i:s');
		$this->assertEquals('26/01/1993 13:14:15', $date);

		$date = Date::format('1993-01-26 13:14:15', 'd/m/Y h:i:s');
		$this->assertEquals('26/01/1993 01:14:15', $date);
	}

	public function testFormatF()
	{
		$date = Date::formatF('1993-01-26 13:14:15', '%d %w %W');
		$this->assertEquals('26 2 04', $date);
	}

	public function testMonth()
	{
		$month = Date::month('1993-01-26 13:14:15');
		$this->assertEquals('janeiro', $month);
	}

	public function testMonthAbbr()
	{
		$month = Date::monthAbbr('1993-01-26 13:14:15');
		$this->assertEquals('jan', $month);

		$month = Date::monthAbbr('1991-09-07');
		$this->assertEquals('set', $month);
	}

	public function testAge()
	{
		$today = '2020-01-01 00:00:00';
		$age = Date::age('1993-01-26 13:14:15', $today);
		$this->assertEquals(26, $age);

		$age = Date::age('1991-09-07', $today);
		$this->assertEquals(28, $age);
	}

	public function testIsValid()
	{
		$valid = Date::isValid('1993-01-26 23:59:59');
		$this->assertTrue($valid);

		$inValid = Date::isValid('1991-02-30 23:59:59');
		$this->assertFalse($inValid);

		$inValid = Date::isValid('2020-01-01 00:30:60');
		$this->assertFalse($inValid);
	}
}
