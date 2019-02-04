<?php

namespace Win\Authentication;

class PasswordTest extends \PHPUnit\Framework\TestCase {

	public function testEncrypt() {
		$string = '100senha';
		$encrypted = Password::encrypt($string);

		$this->assertTrue(strlen($encrypted) > 10);
		$this->assertNotEquals($string, $encrypted);
		$this->assertNotEquals(md5($string), $encrypted);
		$this->assertNotEquals(base64_encode($string), $encrypted);
	}

	public function testEncrypt_Compare() {
		$string = '100senha';
		$this->assertEquals(Password::encrypt($string), Password::encrypt($string));
	}

	public function testGenerate_Length() {
		$this->assertEquals(6, strlen(Password::generate()));
		$this->assertEquals(4, strlen(Password::generate(4)));
		$this->assertEquals(10, strlen(Password::generate(10)));
	}

	public function testGenerate_IsUnique() {
		$this->assertNotEquals(Password::generate(), Password::generate());
	}

}
