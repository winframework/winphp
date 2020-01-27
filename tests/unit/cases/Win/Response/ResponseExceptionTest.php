<?php

namespace Win\Response;

use App\Controllers\ErrorsController;
use PHPUnit\Framework\TestCase;
use Win\Application;

class ResponseExceptionTest extends TestCase
{
	public function testSendResponseWithView()
	{
		ob_start();
		new Application();
		$res = new ResponseException('MSG', 404);
		$res->sendResponse();
		$output = ob_get_clean();

		$this->assertTrue(Application::app()->controller instanceof ErrorsController);
		$this->assertContains('Página não encontrada', $output);
	}

	public function testSendResponseWithoutView()
	{
		ob_start();
		new Application();
		ResponseException::$errorsController = '';
		$res = new ResponseException('MSG', 404);
		$res->sendResponse();
		$output = ob_get_clean();

		$this->assertFalse(Application::app()->controller instanceof ErrorsController);
		$this->assertNotContains('Página não encontrada', $output);
	}
}
