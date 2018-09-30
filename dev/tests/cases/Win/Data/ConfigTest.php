<?php

namespace Win\Data;

class ConfigTest extends \PHPUnit_Framework_TestCase {

	public function testLoad() {
		$values = ['a' => 1, 'b' => 2];
		$config = Config::instance();
		$config->load($values);

		$this->assertEquals(1, $config->get('a'));
		$this->assertEquals(2, $config->get('b', 3));
		$this->assertEquals(null, $config->get('c'));
		$this->assertEquals(3, $config->get('c', 3));
	}

	public function testLoad_Inheritance() {
		$config = Config::instance();
		$config->set('a', 1);

		$data = Data::instance();
		$data->set('a', 10);

		$this->assertEquals(1, $config->get('a'));
		$this->assertEquals(10, $data->get('a'));
	}

}
