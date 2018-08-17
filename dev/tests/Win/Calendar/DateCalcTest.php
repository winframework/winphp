<?php

namespace Win\Calendar;

class DateCalcTest extends \PHPUnit_Framework_TestCase {

	public function testTimeAgoAfter() {
		$date = new DateTime();
		DateCalc::sumSeconds($date, 1);
		$this->assertEquals(DateCalc::toTimeAgo($date), 'daqui a 1 segundo');

		DateCalc::sumMinutes($date, 10);
		$this->assertEquals(DateCalc::toTimeAgo($date), 'daqui a 10 minutos');

		DateCalc::sumHours($date, 1);
		$this->assertEquals(DateCalc::toTimeAgo($date), 'daqui a 1 hora');


		DateCalc::sumDays($date, 4);
		$this->assertEquals(DateCalc::toTimeAgo($date), 'daqui a 4 dias');

		DateCalc::sumWeeks($date, 2);
		$this->assertEquals(DateCalc::toTimeAgo($date), 'daqui a 2 semanas');

		DateCalc::sumMonths($date, 1);
		$this->assertEquals(DateCalc::toTimeAgo($date), 'daqui a 1 mês');

		DateCalc::sumYears($date, 50);
		$this->assertEquals(DateCalc::toTimeAgo($date), 'daqui a 50 anos');
	}

	public function testTimeAgoNow() {
		$date = new DateTime();
		$this->assertEquals(DateCalc::toTimeAgo($date), 'agora mesmo');
	}

	public function testTimeAgoBefore() {
		$date = new DateTime();
		DateCalc::subSeconds($date, 2);
		$this->assertEquals(DateCalc::toTimeAgo($date), '2 segundos atrás');

		DateCalc::subMonths($date, 1);
		$this->assertEquals(DateCalc::toTimeAgo($date), '1 mês atrás');
	}

	public function testConvertSecondsToMinutes() {
		$this->assertEquals(DateCalc::convertSeconds(59), 0);
		$this->assertEquals(DateCalc::convertSeconds(60), 1);
		$this->assertEquals(DateCalc::convertSeconds(130), 2);
		$this->assertEquals(DateCalc::convertSeconds(130, 'i'), 2);
	}

	public function testConvertSecondsToHour() {
		$this->assertEquals(DateCalc::convertSeconds(60 * 59, 'H'), 0);
		$this->assertEquals(DateCalc::convertSeconds(60 * 60, 'H'), 1);
		$this->assertEquals(DateCalc::convertSeconds(60 * 130, 'H'), 2);
	}

	public function testConvertSecondsToDays() {
		$this->assertEquals(DateCalc::convertSeconds(60 * 60 * 5, 'Y'), 0);
		$this->assertEquals(DateCalc::convertSeconds(60 * 60 * 24, 'd'), 1);
		$this->assertEquals(DateCalc::convertSeconds(60 * 60 * 25, 'd'), 1);
		$this->assertEquals(DateCalc::convertSeconds(60 * 60 * 72, 'd'), 3);
	}

	public function testConvertSecondsToYears() {
		$this->assertEquals(DateCalc::convertSeconds(60 * 60 * 24 * 364, 'Y'), 0);
		$this->assertEquals(DateCalc::convertSeconds(60 * 60 * 24 * 365, 'Y'), 1);
		$this->assertEquals(DateCalc::convertSeconds(60 * 60 * 24 * 365 * 2, 'Y'), 2);
	}

}
