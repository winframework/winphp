<?php

namespace Win\Models;

use PHPUnit\Framework\TestCase;
use Win\Common\Template;

class EmailTest extends TestCase
{
	const SUBJECT = 'My Subject';
	const FROM = 'my@email.com';
	const FROM_NAME = 'My Name';

	public function testSubject()
	{
		$email = new Email();
		$email->setSubject(static::SUBJECT);
		$this->assertEquals(static::SUBJECT, $email->getSubject());
	}

	public function testFrom()
	{
		$email = new Email();
		$email->setFrom(static::FROM);
		$this->assertEquals(static::FROM, $email->getFrom());
	}

	public function testFromName()
	{
		$email = new Email();
		$email->setFrom(static::FROM, static::FROM_NAME);
		$this->assertEquals(static::FROM, $email->getFrom());
		$this->assertEquals(static::FROM_NAME, $email->getFromName());
	}

	public function testBcc()
	{
		$emails = ['first@email.com', 'first@reply.com'];
		$email = new Email();
		$email->addBcc($emails[0]);
		$email->addBcc($emails[1]);
		$this->assertEquals($emails, array_keys($email->getBcc()));
	}

	public function testCc()
	{
		$emails = ['first@email.com', 'first@reply.com'];
		$email = new Email();
		$email->addCc($emails[0]);
		$email->addCc($emails[1]);
		$this->assertEquals($emails, array_keys($email->getCc()));
	}

	public function testLanguage()
	{
		$lang = 'FAKE LANGUAGE';
		$email = new Email();
		$email->setLanguage($lang);
		$this->assertEquals($lang, $email->getLanguage());
	}

	public function testAddTo()
	{
		$emails = ['first@email.com', 'second@email.com'];

		$email = new Email();
		$email->addTo($emails[0]);
		$email->addTo($emails[1]);

		$this->assertEquals(count($emails), count($email->getTo()));
		$this->assertTrue(key_exists($emails[1], $email->getTo()));
	}

	public function testReplyTo()
	{
		$emails = ['first@email.com', 'first@reply.com', 'second@reply.com'];
		$email = new Email();

		$email->addTo($emails[0]);
		$email->addReplyTo($emails[1]);
		$email->addReplyTo($emails[2]);

		$this->assertEquals(2, count($email->getReplyTo()));
		$this->assertTrue(key_exists($emails[1], $email->getReplyTo()));
		$this->assertFalse(key_exists($emails[0], $email->getReplyTo()));
	}

	public function testReplyToDefault()
	{
		$addTo = 'first@email.com';
		$email = new Email();

		$email->addTo($addTo);
		$this->assertFalse(key_exists($addTo, $email->getReplyTo()));
	}

	public function testContentString()
	{
		$content = 'A';
		$email = new Email();
		$email->setContent($content);
		$this->assertEquals($content, $email->getContent());
	}

	public function testInvalidContent()
	{
		$email = new Email();
		$email->setContent(new Template('html/this-block-not-exist'));

		$this->assertTrue($email->getContent() instanceof Template);
		$this->assertEquals('', $email->getContent());
	}

	public function testContentTemplate()
	{
		$email = new Email();
		$content = new CustomTemplate();
		$email->setContent($content);

		$this->assertEquals($content, $email->getContent());
	}

	public function testContentConstructor()
	{
		$email = new Email('first');
		$this->assertEquals('My first content', $email->getContent());
	}

	public function testToString()
	{
		$content = 'My content';
		$email = new Email();
		$email->setContent($content);

		$this->assertEquals($content, (string) $email);
	}
}
