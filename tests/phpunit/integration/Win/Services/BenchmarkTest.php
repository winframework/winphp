<?php

namespace Win\Services;

use PHPUnit\Framework\TestCase;

class BenchmarkTest extends TestCase
{
	public function testGetTime()
	{
		$b = new Benchmark();
		$this->assertGreaterThan(0, strlen($b->getTime()));
	}

	public function testGetMemory()
	{
		$b = new Benchmark();
		$b->reset();
		$this->assertGreaterThan(0, strlen($b->getMemory()));
	}

	public function testReset()
	{
		$b = new Benchmark();
		$b->reset();
		$this->assertGreaterThan(0, strlen($b->getTime()));
	}
}
