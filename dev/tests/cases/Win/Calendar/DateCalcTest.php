<?php

namespace Win\Calendar;

use PHPUnit\Framework\TestCase;

class DateCalcTest extends TestCase
{
	public function testTimeAgoAfter()
	{
		$date = new DateTime();
		DateCalc::sumSeconds($date, 1);
		$this->assertEquals('daqui a 1 segundo', DateCalc::toTimeAgo($date));

		DateCalc::sumYears($date, 50);
		$this->assertEquals('daqui a 50 anos', DateCalc::toTimeAgo($date));
	}

	public function testTimeAgoNow()
	{
		$date = new DateTime();
		$this->assertEquals('agora mesmo', DateCalc::toTimeAgo($date));
	}

	public function testTimeAgoFalse()
	{
		$this->assertEquals('indisponível', DateCalc::toTimeAgo(false));
	}

	public function testTimeAgoBefore()
	{
		$date = new DateTime();
		DateCalc::subSeconds($date, 2);
		$this->assertEquals('2 segundos atrás', DateCalc::toTimeAgo($date));

		DateCalc::subMonths($date, 1);
		$this->assertEquals('1 mês atrás', DateCalc::toTimeAgo($date));
	}

	public function testSumTime()
	{
		$dateTime = new DateTime('30-01-2018 12:59:55');

		DateCalc::sumSeconds($dateTime, 1);
		$this->assertEquals(56, $dateTime->format('s'));

		DateCalc::sumMinutes($dateTime, 3);
		$this->assertEquals(02, $dateTime->format('i'));

		DateCalc::sumHours($dateTime, 4);
		$this->assertEquals(17, $dateTime->format('H'));

		DateCalc::sumDays($dateTime, 21);
		$this->assertEquals(20, $dateTime->format('d'));

		DateCalc::sumWeeks($dateTime, 6);
		$this->assertEquals(2, $dateTime->format('w'));

		DateCalc::sumMonths($dateTime, 7);
		$this->assertEquals(11, $dateTime->format('m'));

		DateCalc::sumYears($dateTime, 10);
		$this->assertEquals(2028, $dateTime->format('Y'));
	}

	public function testSubTime()
	{
		$dateTime = new DateTime('30-01-2018 12:59:55');

		DateCalc::subSeconds($dateTime, 1);
		$this->assertEquals(54, $dateTime->format('s'));

		DateCalc::subMinutes($dateTime, 3);
		$this->assertEquals(56, $dateTime->format('i'));

		DateCalc::subHours($dateTime, 4);
		$this->assertEquals(8, $dateTime->format('H'));

		DateCalc::subDays($dateTime, 21);
		$this->assertEquals(9, $dateTime->format('d'));

		DateCalc::subWeeks($dateTime, 6);
		$this->assertEquals(2, $dateTime->format('w'));

		DateCalc::subMonths($dateTime, 7);
		$this->assertEquals(4, $dateTime->format('m'));

		DateCalc::subYears($dateTime, 10);
		$this->assertEquals(2007, $dateTime->format('Y'));
	}

	public function testConvertSecondsToMinutes()
	{
		$this->assertEquals(0, DateCalc::secondsToFormat(59));
		$this->assertEquals(1, DateCalc::secondsToFormat(60));
		$this->assertEquals(2, DateCalc::secondsToFormat(130));
		$this->assertEquals(2, DateCalc::secondsToFormat(130, 'i'));
	}

	public function testConvertSecondsToHour()
	{
		$this->assertEquals(0, DateCalc::secondsToFormat(60 * 59, 'H'));
		$this->assertEquals(1, DateCalc::secondsToFormat(60 * 60, 'H'));
		$this->assertEquals(2, DateCalc::secondsToFormat(60 * 130, 'H'));
	}

	public function testConvertSecondsToDays()
	{
		$this->assertEquals(0, DateCalc::secondsToFormat(60 * 60 * 5, 'Y'));
		$this->assertEquals(1, DateCalc::secondsToFormat(60 * 60 * 24, 'd'));
		$this->assertEquals(1, DateCalc::secondsToFormat(60 * 60 * 25, 'd'));
		$this->assertEquals(3, DateCalc::secondsToFormat(60 * 60 * 72, 'd'));
	}

	public function testConvertSecondsToYears()
	{
		$dayTime = 60 * 60 * 24;
		$this->assertEquals(0, DateCalc::secondsToFormat($dayTime * 364, 'Y'));
		$this->assertEquals(1, DateCalc::secondsToFormat($dayTime * 365, 'Y'));
		$this->assertEquals(2, DateCalc::secondsToFormat($dayTime * 365 * 2, 'Y'));
	}
}
