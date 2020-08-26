<?php

namespace Win\Templates;

use PHPUnit\Framework\TestCase;
use Win\Services\Alert;

class AlertBlockTest extends TestCase
{
	public function testGetDataAlerts()
	{
		$group = 'group2';
		$alerts = [
			'success' => ['Success Group 2'],
			'error' => ['Error 1', 'Error 2'],
		];
		$alertBlock = new AlertBlock($alerts);

		$this->assertEquals($alerts, $alertBlock->get('alerts'));
	}
}
