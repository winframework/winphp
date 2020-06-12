<?php

namespace Win\Common\Traits;

use PHPUnit\Framework\TestCase;

class ArrayDotTraitTest extends TestCase
{
	public function testGet()
	{
		$values = ['a' => 1, 'b' => ['b1' => 2, 'b2' => 3]];
		$data = new ArrClass($values);

		$this->assertEquals($values['a'], $data->get('a'));
		$this->assertEquals($values['b']['b2'], $data->get('b.b2'));
	}

	public function testSet()
	{
		$values = ['a' => 1, 'b' => ['b1' => 2]];
		$data = new ArrClass($values);

		$NEW_A = 10;
		$NEW_B2 = 30;
		$data->set('a', $NEW_A);
		$data->set('b.b2', $NEW_B2);

		$this->assertEquals($NEW_A, $data->get('a'));
		$this->assertEquals($NEW_B2, $data->get('b.b2'));
	}

	public function testAll()
	{
		$values = ['a' => 1, 'b' => ['b1' => 2]];
		$data = new ArrClass($values);

		$this->assertEquals($values, $data->all());
	}

	public function testClear()
	{
		$values = ['a' => 1];
		$data = new ArrClass($values);
		$this->assertEquals($values['a'], $data->get('a'));

		$data->clear();
		$this->assertNotEquals($values['a'], $data->get('a'));
	}

	public function testDelete()
	{
		$values = ['a' => 1, 'b' => 2];
		$data = new ArrClass($values);

		$this->assertEquals($values['a'], $data->get('a'));
		$data->delete('a');
		$data->delete('c');
		$this->assertEquals(null, $data->get('a'));
		$this->assertEquals($values['b'], $data->get('b'));
	}

	public function testHas()
	{
		$data = new ArrClass(['a' => 1, 'b' => ['b1' => 2]]);

		$this->assertTrue($data->has('b'));
		$this->assertTrue($data->has('b.b1'));
		$this->assertFalse($data->has('b.b2'));
	}

	public function testIsEmpty()
	{
		$data = new ArrClass(['a' => 1]);

		$this->assertFalse($data->isEmpty());
		$data->clear();
		$this->assertTrue($data->isEmpty());
	}

	public function testAdd()
	{
		$data = new ArrClass();

		$data->set('a', 2);
		$data->add('a', 3);
		$data->add('a', 4);
		$this->assertCount(3, $data->get('a'));
	}
}
