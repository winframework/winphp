<?php

namespace Win\Services;

use Exception;
use PHPUnit\Framework\TestCase;
use Win\Application;

class AlertTest extends TestCase
{
	public function setUp()
	{
		new Application();
	}

	public function testAllWithTypes()
	{
		$default = 'DEFAULT 1';
		$success1 = 'SUCESSO 1';
		$success2 = 'SUCESSO 2';
		$error = 'ERRO 1';
		$info = 'INFO 1';
		$warning = 'WARNING 1';

		Alert::add($default);
		Alert::success($success1);
		Alert::success($success2);
		Alert::error($error);
		Alert::info($info);
		Alert::warning($warning);

		$alerts = Alert::popAll();

		$this->assertEquals([$default], $alerts['default']);
		$this->assertEquals([$success1, $success2], $alerts['success']);
		$this->assertEquals([$error], $alerts['danger']);
		$this->assertEquals([$info], $alerts['info']);
		$this->assertEquals([$warning], $alerts['warning']);
	}

	public function testErrorException()
	{
		$exception = new Exception('Message', 100);
		Alert::error($exception);

		$alerts = Alert::popAll();
		$this->assertEquals([$exception->getMessage()], $alerts['danger']);
	}

	public function testAddGroups()
	{
		$messages = ['default' => ['MSG 01'], 'success' => ['MSG 02']];
		$productMessages = ['success' => ['MSG 03', 'MSG 04']];

		Alert::add($messages['default'][0]);
		Alert::add($productMessages['success'][0], 'success', 'product');
		Alert::add($messages['success'][0], 'success');
		Alert::add($productMessages['success'][1], 'success', 'product');

		$alerts = Alert::popAll();
		$productAlerts = Alert::popAll('product');

		$this->assertEquals($messages, $alerts);
		$this->assertEquals($productMessages, $productAlerts);
	}
}
