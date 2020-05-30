<?php

namespace Win\Repositories\Database;

use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase
{
	public function testGetValues()
	{
		$values = ['a', 'b'];
		$rules = [10, 'john'];

		$q = new Query('t1', $values);
		$q->addWhere('id', $rules[0]);
		$q->addWhere('name', $rules[1]);

		$this->assertEquals($values + $rules, $q->getValues());
	}

	public function testRaw()
	{
		$values = ['a', 'b'];
		$raw = 'SELECT id,name,email FROM t1 WHERE id = ? AND name LIKE = ?';
		$q = new Query('t1', $values, $raw);
		$q->setOrderBy('name DESC');

		$this->assertEquals(
			$raw,
			$q->raw()
		);
	}

	public function testRawSelect()
	{
		$values = ['a', 'b'];
		$raw = 'SELECT id,name,email FROM t1';
		$q = new Query('t1', $values, $raw);
		$q->addWhere('name < ?', 10);
		$q->setLimit(0, 5);

		$this->assertEquals(
			$raw . ' WHERE (name < ?) LIMIT 0,5',
			$q->select()
		);
	}

	public function testSelect()
	{
		$q = new Query('t1');
		$q->addWhere('id', 10);

		$this->assertEquals(
			'SELECT * FROM t1 WHERE (id = ?)',
			$q->select()
		);
	}

	public function testSelectCount()
	{
		$q = new Query('t1');
		$q->addWhere('id', 10);

		$this->assertEquals(
			'SELECT COUNT(*) FROM t1 WHERE (id = ?)',
			$q->selectCount()
		);
	}

	public function testInsert()
	{
		$values = [
			'id' => 10,
			'name' => 'john',
		];
		$q = new Query('t1', $values);

		$this->assertEquals(
			'INSERT INTO t1 (id,name) VALUES (?, ?)',
			$q->insert()
		);
	}

	public function testUpdate()
	{
		$values = [
			'id' => 10,
			'name' => 'john',
		];
		$q = new Query('t1', $values);

		$this->assertEquals(
			'UPDATE t1 SET id = ?, name = ?',
			$q->update()
		);
	}

	public function testDelete()
	{
		$q = new Query('t1', ['fakeData']);
		$q->addWhere('id', 10);

		$this->assertEquals(
			'DELETE FROM t1 WHERE (id = ?)',
			$q->delete()
		);
	}

	public function testSetLimit()
	{
		$q = new Query('t1', ['fakeData']);
		$q->setLimit(10, 5);

		$this->assertEquals(
			'SELECT * FROM t1 LIMIT 10,5',
			$q->select()
		);
	}

	public function testAddOrderBy()
	{
		$q = new Query('t1', ['fakeData']);
		$q->addOrderBy('id ASC', 3);
		$q->addOrderBy('name DESC', 1);
		$q->addOrderBy('email ASC', 2);

		$this->assertEquals(
			'SELECT * FROM t1 ORDER BY name DESC, email ASC, id ASC',
			$q->select()
		);
	}

	public function testSetOrderBy()
	{
		$q = new Query('t1', ['fakeData']);
		$q->addOrderBy('id ASC', 3);
		$q->setOrderBy('name DESC');

		$this->assertEquals(
			'SELECT * FROM t1 ORDER BY name DESC',
			$q->select()
		);
	}
}
