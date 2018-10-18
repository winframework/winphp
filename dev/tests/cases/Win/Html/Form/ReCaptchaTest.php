<?php

namespace Win\Html\Form;

class ReCaptchaTest extends \PHPUnit_Framework_TestCase {

	public function testValid_NoSecretKey() {
		ReCaptcha::$secretKey = false;
		ReCaptcha::$siteKey = 'my-key';
		$this->assertTrue(ReCaptcha::isValid());
	}

	public function testValid_NoSiteKey() {
		ReCaptcha::$secretKey = 'my-secret';
		ReCaptcha::$siteKey = false;
		$this->assertTrue(ReCaptcha::isValid());
	}

	public function testValid_EmptySecretKey() {
		ReCaptcha::$secretKey = '';
		ReCaptcha::$siteKey = 'my-key';
		$this->assertTrue(ReCaptcha::isValid());
	}

	public function testValid_NoKeys() {
		ReCaptcha::$secretKey = false;
		ReCaptcha::$siteKey = false;
		$this->assertTrue(ReCaptcha::isValid());
	}

	public function testInvalid_WithKeys() {
		ReCaptcha::$secretKey = 'my-secret';
		ReCaptcha::$siteKey = 'my-key';
		$this->assertNotTrue(ReCaptcha::isValid());
	}

}
