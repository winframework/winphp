<?php

namespace Win\Response;

use PHPUnit\Framework\TestCase;
use Win\Application;

class ResponseExceptionTest extends TestCase
{
	public function testSendResponseValid()
	{
		new Application();
		$res = new ResponseException('MSG', 404);
		$res->sendResponse();
	}

	public function testSendResponse404()
	{
		new Application();
		ResponseException::$errorsController = '';
		$res = new ResponseException('MSG', 404);
		$res->sendResponse();
	}
}
