<?php

namespace Win\Repositories;

use PHPUnit\Framework\TestCase;

class AlertTest extends TestCase
{
	public function setUp()
	{
		Alert::instance()->clear();
	}

	public function testAllWithTypes()
	{
		$default = 'DEFAULT 1';
		$success1 = 'SUCESSO 1';
		$success2 = 'SUCESSO 2';
		$error = 'ERRO 1';
		$info = 'INFO 1';
		$warning = 'WARNING 1';

		Alert::default($default);
		Alert::success($success1);
		Alert::success($success2);
		Alert::error($error);
		Alert::info($info);
		Alert::warning($warning);

		$alerts = Alert::instance()->all();

		$this->assertEquals([$default], $alerts['default']);
		$this->assertEquals([$success1, $success2], $alerts['success']);
		$this->assertEquals([$error], $alerts['danger']);
		$this->assertEquals([$info], $alerts['info']);
		$this->assertEquals([$warning], $alerts['warning']);
	}

	public function testAddDifferentInstances()
	{
		$messages = ['default' => ['MSG 01'], 'success' => ['MSG 02']];
		$productMessages = ['success' => ['MSG 03', 'MSG 04']];

		Alert::instance()->add('default', $messages['default'][0]);
		Alert::instance('product')->add('success', $productMessages['success'][0]);
		Alert::instance()->add('success', $messages['success'][0]);
		Alert::instance('product')->add('success', $productMessages['success'][1]);

		$alerts = Alert::instance()->all();
		$productAlerts = Alert::instance('product')->all();

		$this->assertEquals($messages, $alerts);
		$this->assertEquals($productMessages, $productAlerts);
	}
}
