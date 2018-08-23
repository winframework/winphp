<?php

namespace Win\Html\Form;

class ReCaptchaTest extends \PHPUnit_Framework_TestCase {

	public function testValidWithoutKeys() {
		ReCaptcha::$secretKey = false;
		ReCaptcha::$siteKey = 'my-key';
		$this->assertTrue(ReCaptcha::isValid());

		ReCaptcha::$secretKey = 'my-secret';
		ReCaptcha::$siteKey = false;
		$this->assertTrue(ReCaptcha::isValid());

		ReCaptcha::$secretKey = '';
		ReCaptcha::$siteKey = 'my-key';
		$this->assertTrue(ReCaptcha::isValid());

		ReCaptcha::$secretKey = false;
		ReCaptcha::$siteKey = false;
		$this->assertTrue(ReCaptcha::isValid());
	}

	public function testInvalidWithKeys() {
		ReCaptcha::$secretKey = 'my-secret';
		ReCaptcha::$siteKey = 'my-key';
		$this->assertNotTrue(ReCaptcha::isValid());
	}

}
