<?php

namespace Win\Services;

use Exception;
use PHPUnit\Framework\TestCase;
use Win\Application;

class AlertTest extends TestCase
{
	public function setUp(): void
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

}
