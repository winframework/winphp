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
}
