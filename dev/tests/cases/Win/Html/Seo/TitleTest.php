<?php

namespace Win\Html\Seo;

use PHPUnit\Framework\TestCase;
use Win\Mvc\Application;

class TitleTest extends TestCase
{
	const LONGEST_TITLE = 'My custom and longest Title and longest '
	. 'Title and longest Title and longest Title and longest '
	. 'Title and longest Title';

	public function testOtimize()
	{
		$this->assertEquals('My custom Title', Title::otimize('My custom Title', 100));
		$this->assertEquals('My custom...', Title::otimize('My custom Title', 11));
		$this->assertEquals('My custom...', Title::otimize('My custom Title', 10));
		$this->assertEquals('My custom...', Title::otimize('My custom Title', 9));
		$this->assertEquals('My...', Title::otimize('My custom Title', 8));
	}

	public function testOtimizePrefix()
	{
		Title::$prefix = '|| ';
		Title::$suffix = '';
		$this->assertEquals(
			'|| My custom Title',
			Title::otimize('My custom Title', 100)
		);
		$this->assertEquals('|| My...', Title::otimize('My custom Title', 11));
	}

	public function testOtimizeSuffix()
	{
		Title::$prefix = '';
		Title::$suffix = ' ||';
		$this->assertEquals(
			'My custom Title ||',
			Title::otimize('My custom Title', 100)
		);
		$this->assertEquals('My... ||', Title::otimize('My custom Title', 11));
	}

	public function testOtimizeSuffixAndPrefix()
	{
		Title::$prefix = '|| ';
		Title::$suffix = ' ||';
		$this->assertEquals(
			'|| My custom Title ||',
			Title::otimize('My custom Title', 100)
		);
		$this->assertEquals('|| My... ||', Title::otimize('My custom Title', 13));
		$this->assertEquals('|| ... ||', Title::otimize('My custom Title', 7));
	}

	public function testSetTitle()
	{
		$app = new Application();
		$app->controller->setTitle('My old page Title');
		Title::$prefix = '.:: ';
		Title::$suffix = ' ::.';
		Title::setTitle(static::LONGEST_TITLE);
		$title = $app->controller->getData('title');

		$this->assertEquals(
			'.:: My custom and longest Title and longest Title and longest... ::.',
			$title
		);
	}
}
