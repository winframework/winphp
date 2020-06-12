<?php

namespace Win;

use App\Controllers\ErrorsController;
use PHPUnit\Framework\TestCase;
use Win\Application;

class HttpExceptionTest extends TestCase
{
	public function testRunWithView()
	{
		ob_start();
		new Application();
		$ex = new HttpException('MSG', 404);
		$ex->run();
		$output = ob_get_clean();

		$this->assertTrue(Application::app()->controller instanceof ErrorsController);
		$this->assertContains('Página não encontrada', $output);
	}

	public function testRunWithoutView()
	{
		HttpException::$controller = '';
		ob_start();
		new Application();
		$ex = new HttpException('MSG', 404);
		$ex->run();
		$output = ob_get_clean();

		$this->assertFalse(Application::app()->controller instanceof ErrorsController);
		$this->assertNotContains('Página não encontrada', $output);
	}
}
