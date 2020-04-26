<?php

namespace Win\Common;

use PHPUnit\Framework\TestCase;
use Win\Request\CustomUrl;
use Win\Request\Url;

class DependenceInjectorTest extends TestCase
{
	const FAKE_SEGMENTS = ['FAKE SEGMENTS'];

	public function tearDown()
	{
		DependenceInjector::$container = [];
	}

	public function testGetClassDi()
	{
		DependenceInjector::$container = [
			'Win\\Request\\Url' => 'Win\\Request\\CustomUrl',
		];

		CustomUrl::$fakeSegments = static::FAKE_SEGMENTS;
		$segments = Url::instance('UNIQUE_INSTANCE')->getSegments();

		$this->assertEquals(static::FAKE_SEGMENTS, $segments);
	}
}
