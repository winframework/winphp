<?php

namespace Win\Repositories\Database;

use PHPUnit\Framework\TestCase;

class SqlTest extends TestCase
{
	public function testValues()
	{
		$values = ['a', 'b'];
		$rules = [10, 'john'];

		$sql = new Sql('t1', $values);
		$sql->addWhere('id', [$rules[0]]);
		$sql->addWhere('name', [$rules[1]]);

		$this->assertEquals($values + $rules, $sql->values());
	}

	public function testJoin()
	{
		$join1 = 'JOIN a ON a.col1 = a.col2';
		$join2 = 'LEFT JOIN b ON b.col1 = b.col2';
		$sql = new Sql('t1');

		$sql->addJoin($join1);
		$sql->addJoin($join2);
		$sql->setOrderBy('name DESC');

		$this->assertEquals(
			'SELECT * FROM t1 ' . $join1 . ' ' . $join2 . ' ORDER BY name DESC',
			$sql->select(),
		);
	}

	public function testSelect()
	{
		$sql = new Sql('t1');
		$sql->columns = ['id', 'name', 'email'];

		$this->assertEquals('SELECT id, name, email FROM t1', $sql->select());
	}

	public function testSelectCount()
	{
		$sql = new Sql('t1');
		$sql->addWhere('id', [10]);

		$this->assertEquals(
			'SELECT COUNT(*) FROM t1 WHERE (id = ?)',
			$sql->selectCount()
		);
	}

	public function testInsert()
	{
		$values = [
			'id' => 10,
			'name' => 'john',
		];
		$sql = new Sql('t1', $values);

		$this->assertEquals(
			'INSERT INTO t1 (id,name) VALUES (?, ?)',
			$sql->insert()
		);
	}

	public function testUpdate()
	{
		$values = [
			'id' => 10,
			'name' => 'john',
		];
		$sql = new Sql('t1', $values);

		$this->assertEquals(
			'UPDATE t1 SET id = ?, name = ?',
			$sql->update()
		);
	}

	public function testDelete()
	{
		$sql = new Sql('t1', ['fakeData']);
		$sql->addWhere('id', [10]);

		$this->assertEquals(
			'DELETE FROM t1 WHERE (id = ?)',
			$sql->delete()
		);
	}

	public function testSetLimit()
	{
		$sql = new Sql('t1', ['fakeData']);
		$sql->setLimit(10, 5);

		$this->assertEquals(
			'SELECT * FROM t1 LIMIT 10,5',
			$sql->select()
		);
	}

	public function testAddOrderBy()
	{
		$sql = new Sql('t1', ['fakeData']);
		$sql->addOrderBy('id ASC', 3);
		$sql->addOrderBy('name DESC', 1);
		$sql->addOrderBy('email ASC', 2);

		$this->assertEquals(
			'SELECT * FROM t1 ORDER BY name DESC, email ASC, id ASC',
			$sql->select()
		);
	}

	public function testSetOrderBy()
	{
		$sql = new Sql('t1', ['fakeData']);
		$sql->addOrderBy('id ASC', 3);
		$sql->setOrderBy('name DESC');

		$this->assertEquals(
			'SELECT * FROM t1 ORDER BY name DESC',
			$sql->select()
		);
	}
}
