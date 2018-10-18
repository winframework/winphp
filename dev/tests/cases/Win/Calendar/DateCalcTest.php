<?php

namespace Win\Calendar;

class DateCalcTest extends \PHPUnit_Framework_TestCase {

	public function testTimeAgoAfter() {
		$date = new DateTime();
		DateCalc::sumSeconds($date, 1);
		$this->assertEquals(DateCalc::toTimeAgo($date), 'daqui a 1 segundo');

		DateCalc::sumYears($date, 50);
		$this->assertEquals(DateCalc::toTimeAgo($date), 'daqui a 50 anos');
	}

	public function testTimeAgoNow() {
		$date = new DateTime();
		$this->assertEquals(DateCalc::toTimeAgo($date), 'agora mesmo');
	}

	public function testTimeAgoFalse() {
		$this->assertEquals(DateCalc::toTimeAgo(false), 'indisponível');
	}

	public function testTimeAgoBefore() {
		$date = new DateTime();
		DateCalc::subSeconds($date, 2);
		$this->assertEquals(DateCalc::toTimeAgo($date), '2 segundos atrás');

		DateCalc::subMonths($date, 1);
		$this->assertEquals(DateCalc::toTimeAgo($date), '1 mês atrás');
	}

	public function testSumTime() {
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

	public function testSubTime() {
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

	public function testConvertSecondsToMinutes() {
		$this->assertEquals(DateCalc::secondsToFormat(59), 0);
		$this->assertEquals(DateCalc::secondsToFormat(60), 1);
		$this->assertEquals(DateCalc::secondsToFormat(130), 2);
		$this->assertEquals(DateCalc::secondsToFormat(130, 'i'), 2);
	}

	public function testConvertSecondsToHour() {
		$this->assertEquals(DateCalc::secondsToFormat(60 * 59, 'H'), 0);
		$this->assertEquals(DateCalc::secondsToFormat(60 * 60, 'H'), 1);
		$this->assertEquals(DateCalc::secondsToFormat(60 * 130, 'H'), 2);
	}

	public function testConvertSecondsToDays() {
		$this->assertEquals(DateCalc::secondsToFormat(60 * 60 * 5, 'Y'), 0);
		$this->assertEquals(DateCalc::secondsToFormat(60 * 60 * 24, 'd'), 1);
		$this->assertEquals(DateCalc::secondsToFormat(60 * 60 * 25, 'd'), 1);
		$this->assertEquals(DateCalc::secondsToFormat(60 * 60 * 72, 'd'), 3);
	}

	public function testConvertSecondsToYears() {
		$this->assertEquals(DateCalc::secondsToFormat(60 * 60 * 24 * 364, 'Y'), 0);
		$this->assertEquals(DateCalc::secondsToFormat(60 * 60 * 24 * 365, 'Y'), 1);
		$this->assertEquals(DateCalc::secondsToFormat(60 * 60 * 24 * 365 * 2, 'Y'), 2);
	}

}
