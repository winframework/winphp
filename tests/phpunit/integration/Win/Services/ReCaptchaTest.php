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
		define('RECAPTCHA_SITE_KEY', '6LcDAioUAAAAAIMAHCFz02fuq7at3C6gf9_DIGum');
		define('RECAPTCHA_SECRET_KEY', '6LcDAioUAAAAAKLXofatfq3FP2TLkgkIQSbJwto0');
		$this->assertNotTrue($recaptcha->isValid());
	}
}
