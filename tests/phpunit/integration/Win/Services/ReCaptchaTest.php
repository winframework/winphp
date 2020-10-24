<?php

namespace Win\Services;

use PHPUnit\Framework\TestCase;

class ReCaptchaTest extends TestCase
{
	public function testValid()
	{
		$recaptcha = ReCaptcha::instance();
		$this->assertTrue($recaptcha->isValid());
	}

	public function testInvalid()
	{
		$recaptcha = ReCaptcha::instance();
		define('RECAPTCHA_SITE_KEY', 'aaaaaaaa');
		define('RECAPTCHA_SECRET_KEY', 'bbbbbbbb');
		$this->assertNotTrue($recaptcha->isValid());
	}
}
