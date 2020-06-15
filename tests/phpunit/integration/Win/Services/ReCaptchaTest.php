<?php

namespace Win\Services;

use PHPUnit\Framework\TestCase;

class ReCaptchaTest extends TestCase
{
	public function testValidNoSecretKey()
	{
		ReCaptcha::$secretKey = false;
		ReCaptcha::$siteKey = 'my-key';
		$this->assertTrue(ReCaptcha::isValid());
	}

	public function testValidNoSiteKey()
	{
		ReCaptcha::$secretKey = 'my-secret';
		ReCaptcha::$siteKey = false;
		$this->assertTrue(ReCaptcha::isValid());
	}

	public function testValidEmptySecretKey()
	{
		ReCaptcha::$secretKey = '';
		ReCaptcha::$siteKey = 'my-key';
		$this->assertTrue(ReCaptcha::isValid());
	}

	public function testValidNoKeys()
	{
		ReCaptcha::$secretKey = false;
		ReCaptcha::$siteKey = false;
		$this->assertTrue(ReCaptcha::isValid());
	}

	public function testInvalid()
	{
		ReCaptcha::$secretKey = 'my-secret';
		ReCaptcha::$siteKey = 'my-key';
		$this->assertNotTrue(ReCaptcha::isValid());
	}
}
