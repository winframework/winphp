<?php

namespace Win\Validation;

use PHPUnit\Framework\TestCase;

class RulesTest extends TestCase
{
	public function testRequired()
	{
		$this->assertFalse(Rules::isValid('', 'required'));
		
		$this->assertTrue(Rules::isValid('teste', 'required'));
	}

	public function testEmail()
	{
		$this->assertFalse(Rules::isValid('ful.com@', 'email'));
		$this->assertFalse(Rules::isValid('@email', 'email'));

		$this->assertTrue(Rules::isValid('fulano@email.com', 'email'));
	}

	public function testInt()
	{
		$this->assertFalse(Rules::isValid('', 'int'));
		$this->assertFalse(Rules::isValid('0', 'int'));
		$this->assertFalse(Rules::isValid('0teste', 'int'));
		$this->assertFalse(Rules::isValid('teste', 'int'));

		$this->assertTrue(Rules::isValid(0, 'int'));
		$this->assertTrue(Rules::isValid(20, 'int'));
	}

	public function testMin()
	{
		$this->assertFalse(Rules::isValid('19', 'min:20'));
		$this->assertFalse(Rules::isValid('-21', 'min:20'));

		$this->assertTrue(Rules::isValid('20', 'min:20'));
		$this->assertTrue(Rules::isValid('21', 'min:20'));
	}

	public function testMax()
	{
		$this->assertFalse(Rules::isValid('21', 'max:20'));

		$this->assertTrue(Rules::isValid('20', 'max:20'));
		$this->assertTrue(Rules::isValid('0', 'max:20'));
		$this->assertTrue(Rules::isValid('-2', 'max:20'));
	}

	public function testChecked()
	{
		$this->assertFalse(Rules::isValid('', 'checked'));
		$this->assertFalse(Rules::isValid('0', 'checked'));
		$this->assertFalse(Rules::isValid(null, 'checked'));
		$this->assertFalse(Rules::isValid(false, 'checked'));

		$this->assertTrue(Rules::isValid('1', 'checked'));
		$this->assertTrue(Rules::isValid(10, 'checked'));
		$this->assertTrue(Rules::isValid(true, 'checked'));
		$this->assertTrue(Rules::isValid('true', 'checked'));
		$this->assertTrue(Rules::isValid('false', 'checked'));
		$this->assertTrue(Rules::isValid('teste', 'checked'));
	}
}
