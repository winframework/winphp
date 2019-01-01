<?php

namespace Win\Data;

class DataTest extends \PHPUnit\Framework\TestCase {

	public function testGet() {
		$data = Data::instance();
		$data->set('a', 1);
		$data->set('b', 2);

		$this->assertEquals(1, $data->get('a'));
		$this->assertEquals(2, $data->get('b'));
	}

	public function testGet_MultiInstance() {
		$data = Data::instance();
		$data->set('a', 1);
		$data->set('b', 2);

		Data::instance('second')->set('a', 11);

		$this->assertEquals(1, $data->get('a'));
		$this->assertEquals(2, $data->get('b'));
		$this->assertEquals(11, Data::instance('second')->get('a'));
		$this->assertEquals(null, Data::instance('second')->get('b'));
	}

	public function testGetDefault() {
		$data = Data::instance();
		$data->set('a', 1);
		$data->set('b', 2);

		$this->assertEquals(2, $data->get('b', 3));
		$this->assertEquals(null, $data->get('c'));
		$this->assertEquals(3, $data->get('c', 3));
	}

	public function testAll() {
		$data = Data::instance();
		$data->set('a', 1);
		$data->set('b', 2);

		$this->assertEquals(['a' => 1, 'b' => 2], $data->all());
	}

	public function testClear() {
		$data = Data::instance();
		$data->set('a', 1);

		$this->assertEquals(1, $data->get('a'));
		$data->clear();
		$this->assertEquals(null, $data->get('a'));
	}

	public function testDelete() {
		$data = Data::instance();
		$data->set('a', 1);
		$data->set('b', 2);

		$this->assertEquals(1, $data->get('a'));
		$data->delete('a');
		$data->delete('c');
		$this->assertEquals(null, $data->get('a'));
		$this->assertEquals(2, $data->get('b'));
	}

	public function testGetArray() {
		$data = Data::instance();
		$data->clear();
		$data->set('a', ['a' => 11]);
		$this->assertEquals(11, $data->get('a.a'));
	}

	public function testSetArray() {
		$data = Data::instance();
		$data->clear();
		$data->set('a.a', 11);
		$this->assertEquals(11, $data->get('a.a'));
	}

	public function testArrayMultiLevel() {
		$data = Data::instance();
		$data->clear();
		$data->set('a.a.a', 111);
		$data->set('a.a.b', 112);
		$data->set('a.b.a', 121);

		$this->assertEquals(111, $data->get('a.a.a'));
		$this->assertEquals(121, $data->get('a.b.a'));
		$this->assertEquals(null, $data->get('a.b.b'));
	}

	public function testHas() {
		$session = Data::instance();
		$session->clear();
		$session->set('a.b', 2);
		$this->assertTrue($session->has('a'));
		$this->assertTrue($session->has('a.b'));
	}

	public function testNotHas() {
		$session = Data::instance();
		$session->clear();
		$session->set('a.b', 2);
		$this->assertFalse($session->has('c'));
		$this->assertFalse($session->has('a.c'));
	}

	public function testAdd() {
		$session = Data::instance();
		$session->clear();
		$session->set('a', 2);
		$session->add('a', 3);
		$session->add('a', 4);
		$this->assertCount(3, $session->get('a'));
	}

}
