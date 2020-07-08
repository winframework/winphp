<?php

namespace Win\Repositories\Database;

use PHPUnit\Framework\TestCase;

class SqlTest extends TestCase
{
	public function testValues()
	{
		$values = ['a', 'b'];
		$rules = [10, 'john'];
		$table = 't1';

		$sql = new Sql($table);
		$sql->setValues($values);
		$sql->addWhere('id', [$rules[0]]);
		$sql->addWhere('name', [$rules[1]]);

		$this->assertEquals(array_merge($values, $rules), $sql->values());
	}

	public function testJoin()
	{
		$join1 = 'JOIN a ON a.col1 = a.col2';
		$join2 = 'LEFT JOIN b ON b.col1 = b.col2';
		$table = 't1';
		$sql = new Sql($table);

		$sql->addJoin($join1,[]);
		$sql->addJoin($join2, []);
		$sql->setOrderBy('name DESC');

		$this->assertEquals(
			'SELECT * FROM t1 ' . $join1 . ' ' . $join2 . ' ORDER BY name DESC',
			$sql->select(),
		);
	}

	public function testSelect()
	{
		$table = 't1';
		$sql = new Sql($table);
		$sql->columns = ['id', 'name', 'email'];

		$this->assertEquals('SELECT id, name, email FROM t1', $sql->select());
	}

	public function testSetValues()
	{
		$values1 = ['a1', 'b1'];
		$values2 = ['a2', 'b2'];
		$table = 't1';
		$sql = new Sql($table,$values1);
		$sql->setValues($values2);
		$this->assertEquals($values2, $sql->values());
	}

	public function testSelectCount()
	{
		$table = 't1';
		$sql = new Sql($table);
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
		$table = 't1';
		$sql = new Sql($table);
		$sql->setValues($values);

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
		$table = 't1';
		$sql = new Sql($table);
		$sql->setValues($values);

		$this->assertEquals(
			'UPDATE t1 SET id = ?, name = ?',
			$sql->update()
		);
	}

	public function testDelete()
	{
		$table = 't1';
		$sql = new Sql($table);
		$sql->addWhere('id', [10]);

		$this->assertEquals(
			'DELETE FROM t1 WHERE (id = ?)',
			$sql->delete()
		);
	}

	public function testSetLimit()
	{
		$table = 't1';
		$sql = new Sql($table);
		$sql->setLimit(10, 5);

		$this->assertEquals(
			'SELECT * FROM t1 LIMIT 10,5',
			$sql->select()
		);
	}

	public function testAddOrderBy()
	{
		$table = 't1';
		$sql = new Sql($table);
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
		$table = 't1';
		$sql = new Sql($table);
		$sql->addOrderBy('id ASC', 3);
		$sql->setOrderBy('name DESC');

		$this->assertEquals(
			'SELECT * FROM t1 ORDER BY name DESC',
			$sql->select()
		);
	}
}
