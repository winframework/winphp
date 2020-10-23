<?php

namespace Win\Services;

use PHPUnit\Framework\TestCase;

class ReCaptchaTest extends TestCase
{
	public function testValidNoSecretKey()
	{
		$recaptcha = Recaptcha::instance(); 
		$recaptcha->secretKey = false;
		$recaptcha->siteKey = 'my-key';
		$this->assertTrue($recaptcha->isValid());
	}

	public function testValidNoSiteKey()
	{
		$recaptcha = Recaptcha::instance();
		$recaptcha->secretKey = 'my-secret';
		$recaptcha->siteKey = false;
		$this->assertTrue($recaptcha->isValid());
	}

	public function testValidEmptySecretKey()
	{
		$recaptcha = Recaptcha::instance();
		$recaptcha->secretKey = '';
		$recaptcha->siteKey = 'my-key';
		$this->assertTrue($recaptcha->isValid());
	}

	public function testValidNoKeys()
	{
		$recaptcha = Recaptcha::instance();
		$recaptcha->secretKey = false;
		$recaptcha->siteKey = false;
		$this->assertTrue($recaptcha->isValid());
	}

	public function testInvalid()
	{
		$recaptcha = Recaptcha::instance();
		$recaptcha->secretKey = 'my-secret';
		$recaptcha->siteKey = 'my-key';
		$this->assertNotTrue($recaptcha->isValid());
	}
}
