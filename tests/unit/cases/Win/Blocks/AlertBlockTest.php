<?php

namespace Win\Blocks;

use PHPUnit\Framework\TestCase;
use Win\Repositories\Alert;

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

		Alert::instance()->add('success', 'Success Default');
		Alert::instance('group1')->add('success', 'Success Group 1');

		Alert::instance($group)->add($keys[0], array_values($alerts)[0][0]);
		Alert::instance($group)->add($keys[1], array_values($alerts)[1][0]);
		Alert::instance($group)->add($keys[1], array_values($alerts)[1][1]);

		$alertBlock = new AlertBlock($group);

		$this->assertEquals(count($alerts), count($alertBlock->getData('alerts')));
		$this->assertEquals($alerts, $alertBlock->getData('alerts'));
	}
}
