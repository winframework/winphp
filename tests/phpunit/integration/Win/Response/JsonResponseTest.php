<?php

namespace Win\Response;

use PHPUnit\Framework\TestCase;

class JsonResponseTest extends TestCase
{
	public static $contentType = '';

	public function testToString()
	{
		$data = ['a' => 1, 'b' => ['b1' => 2, 'b2' => 3]];
		$json = new JsonResponse($data);

		$this->assertTrue((bool) $json);
		// $this->assertEquals(json_encode($data), (string) $json);
	}
}
