<?php

namespace Win\Authentication;

use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
	public function testEncrypt()
	{
		$string = '100senha';
		$encrypted = Password::encrypt($string);

		$this->assertTrue(strlen($encrypted) > 10);
		$this->assertNotEquals($string, $encrypted);
		$this->assertNotEquals(md5($string), $encrypted);
		$this->assertNotEquals(base64_encode($string), $encrypted);
		$this->assertEquals($encrypted, Password::encrypt($string));
	}

	public function testGenerate()
	{
		$this->assertEquals(6, strlen(Password::generate(6)));
		$this->assertEquals(4, strlen(Password::generate(4)));
		$this->assertEquals(10, strlen(Password::generate(10)));
		$this->assertNotEquals(Password::generate(), Password::generate());
	}
}
