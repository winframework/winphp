<?php

namespace Win\Repositories;

use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
	public function testSet()
	{
		$session = new Session();
		$session->set('a', 1);

		$this->assertEquals(1, $session->get('a'));
		$this->assertEquals(null, $session->get('b'));
	}

	public function testSuperGlobal()
	{
		session_unset();
		$_SESSION['a'] = 1;
		$session = new Session();
		$session->set('b', 2);

		$this->assertEquals(1, $session->get('a'));
		$this->assertEquals(2, $session->get('b', 3));
	}

	public function testAdd()
	{
		session_unset();
		$session = new Session();
		$session->set('b', 2);
		$session->add('b', 3);
		$session->add('b', 4);

		$this->assertEquals([2, 3, 4], $session->get('b'));
	}

	public function testGetDotSintaxe()
	{
		session_unset();
		$_SESSION['a']['b'] = 12;
		$session = new Session();
		$session->set('b', 2);

		$this->assertEquals(12, $session->get('a.b'));
	}

	public function testPop()
	{
		$data = new Session();
		$data->clear();
		$data->set('a', 1);
		$data->set('b', 2);

		$this->assertTrue($data->has('a'));
		$this->assertEquals(1, $data->pop('a'));
		$this->assertFalse($data->has('a'));
		$this->assertEquals(2, $data->get('b'));
	}

	public function testPopAll()
	{
		$data = new Session();
		$data->clear();
		$data->set('a', 1);
		$data->set('b', 2);

		$this->assertCount(2, $data->popAll());
		$this->assertFalse($data->has('a'));
		$this->assertFalse($data->has('b'));
	}

	public function testAll()
	{
		$data = new Session();
		$data->clear();
		$data->set('a', 1);
		$data->set('b', 2);

		$this->assertCount(2, $data->all());
		$this->assertTrue($data->has('a'));
		$this->assertTrue($data->has('b'));
	}

	public function testIsEmpty()
	{
		$data = new Session();
		$data->set('a', 1);

		$this->assertFalse($data->isEmpty());
		$data->clear();

		$this->assertTrue($data->isEmpty());
	}
}
