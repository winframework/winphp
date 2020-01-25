<?php

namespace Win\Common\Utils;

use DateInterval;
use PHPUnit\Framework\TestCase;
use Win\Models\DateTime;

class CalendarTest extends TestCase
{
	public function testConvertSecondsToMinutes()
	{
		$this->assertEquals(0, Calendar::secondsToFormat(59));
		$this->assertEquals(1, Calendar::secondsToFormat(60));
		$this->assertEquals(2, Calendar::secondsToFormat(130));
		$this->assertEquals(2, Calendar::secondsToFormat(130, 'i'));
	}

	public function testConvertSecondsToHour()
	{
		$this->assertEquals(0, Calendar::secondsToFormat(60 * 59, 'H'));
		$this->assertEquals(1, Calendar::secondsToFormat(60 * 60, 'H'));
		$this->assertEquals(2, Calendar::secondsToFormat(60 * 130, 'H'));
	}

	public function testConvertSecondsToDays()
	{
		$this->assertEquals(0, Calendar::secondsToFormat(60 * 60 * 5, 'Y'));
		$this->assertEquals(1, Calendar::secondsToFormat(60 * 60 * 24, 'd'));
		$this->assertEquals(1, Calendar::secondsToFormat(60 * 60 * 25, 'd'));
		$this->assertEquals(3, Calendar::secondsToFormat(60 * 60 * 72, 'd'));
	}

	public function testConvertSecondsToYears()
	{
		$dayTime = 60 * 60 * 24;
		$this->assertEquals(0, Calendar::secondsToFormat($dayTime * 364, 'Y'));
		$this->assertEquals(1, Calendar::secondsToFormat($dayTime * 365, 'Y'));
		$this->assertEquals(2, Calendar::secondsToFormat($dayTime * 365 * 2, 'Y'));
	}

	public function testTimeAgoAfter()
	{
		$date = new DateTime();
		$date->modify('+ 1 second');
		$this->assertEquals('daqui a 1 segundo', Calendar::toTimeAgo($date));

		$date->modify('+ 50 years');
		$this->assertEquals('daqui a 50 anos', Calendar::toTimeAgo($date));
	}

	public function testTimeAgoNow()
	{
		$date = new DateTime();
		$this->assertEquals('agora mesmo', Calendar::toTimeAgo($date));
	}

	public function testTimeAgoFalse()
	{
		$this->assertEquals('indisponível', Calendar::toTimeAgo(false));
	}

	/**
	 * Fevereiro não conta como 30 dias
	 */
	public function testTimeAgoBefore()
	{
		$date = new DateTime();
		$date->modify('- 2 minutes');
		$this->assertEquals('2 minutos atrás', Calendar::toTimeAgo($date));

		$date2 = new DateTime();
		$date2->modify('- 1 month');

		$this->assertContains(
			Calendar::toTimeAgo($date2),
			['1 mês atrás', '4 semanas atrás']
		);
	}
}
