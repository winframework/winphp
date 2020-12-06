<?php

namespace Win\Templates;

use PHPUnit\Framework\TestCase;

class AlertsTest extends TestCase
{
	public function testGetDataAlerts()
	{
		$alerts = [
			'success' => ['Success Group 2'],
			'error' => ['Error 1', 'Error 2'],
		];
		$alertTemplate = new Alerts($alerts);

		$this->assertEquals($alerts, $alertTemplate->get('alerts'));
	}
}
