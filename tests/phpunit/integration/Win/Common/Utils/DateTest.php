<?php

namespace Win\Models;

use PHPUnit\Framework\TestCase;
use Win\Common\Utils\Date;

class DateTest extends TestCase
{
	public static function setUpBeforeClass(): void
	{
		setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	}

	public function testCreate()
	{
		$date = Date::create('Y-m-d H:i:s', '1993-01-26 13:14:15', 'd/m/Y H:i:s');
		$this->assertEquals('26/01/1993 13:14:15', $date);

		$date = Date::create('d/m/y H:i:s', '26/01/93 13:14:15');
		$this->assertEquals('1993-01-26 13:14:15', $date);

		$date = Date::create('Y-m-d', '1993-01-26', 'd/m/y');
		$this->assertEquals('26/01/93', $date);
	}

	public function testFormat()
	{
		$date = Date::format('1993-01-26 13:14:15', 'd/m/Y H:i:s');
		$this->assertEquals('26/01/1993 13:14:15', $date);

		$date = Date::format('1993-01-26 13:14:15', 'd/m/Y h:i:s');
		$this->assertEquals('26/01/1993 01:14:15', $date);

		$date = Date::format('', 'd/m/Y h:i:s');
		$this->assertNull($date);

		$date = Date::format(null, 'd/m/Y h:i:s');
		$this->assertNull($date);
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
