<?php

namespace Win\Blocks;

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
		$keys = array_keys($alerts);

		Alert::add('Success Default', 'success');
		Alert::add('Success Group 1', 'success', 'group1');

		Alert::add(array_values($alerts)[0][0], $keys[0], $group);
		Alert::add(array_values($alerts)[1][0], $keys[1], $group);
		Alert::add(array_values($alerts)[1][1], $keys[1], $group);

		$alertBlock = new AlertBlock($group);

		$this->assertEquals(count($alerts), count($alertBlock->getData('alerts')));
		$this->assertEquals($alerts, $alertBlock->getData('alerts'));
	}
}
