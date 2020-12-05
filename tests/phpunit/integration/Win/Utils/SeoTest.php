<?php

namespace Win\Utils;

use PHPUnit\Framework\TestCase;

class SeoTest extends TestCase
{
	const LONG_STRING = 'My custom and longest Title and longest '
	. 'Title and longest Title and longest Title and longest '
	. 'Title and longest Title';

	public function setUp(): void
	{
		Seo::$description = '';
		Seo::$keywords = [];
		Seo::$titlePrefix = '';
		Seo::$titleSuffix = '';
	}

	public function testKeywords()
	{
		$string = Seo::keywords(['first', 'Second', 'THIRD']);
		$this->assertEquals('first, second, third', $string);

		$string = Seo::keywords(['first', 'SE-cond', 'third'], 16);
		$this->assertEquals('first, se-cond', $string);
	}

	public function testKeywordsDefault()
	{
		Seo::$keywords = ['pre', 'inserted', 'before'];
		$string = Seo::keywords(['first', 'second'], 31);
		$this->assertEquals('first, second, pre, inserted', $string);
	}

	public function testTitle()
	{
		$this->assertEquals('My custom Title', Seo::title('My custom Title', 100));
		$this->assertEquals('My custom...', Seo::title('My custom Title', 11));
		$this->assertEquals('My custom...', Seo::title('My custom Title', 10));
		$this->assertEquals('My custom...', Seo::title('My custom Title', 9));
		$this->assertEquals('My...', Seo::title('My custom Title', 8));
	}

	public function testTitleSuffixAndPrefix()
	{
		Seo::$titlePrefix = '|| ';
		Seo::$titleSuffix = ' ||';
		$this->assertEquals('|| My custom Title ||', Seo::title('My custom Title', 100));
		$this->assertEquals('|| My... ||', Seo::title('My custom Title', 13));
		$this->assertEquals('|| ... ||', Seo::title('My custom Title', 7));
	}

	public function testDescriptionDefault()
	{
		Seo::$description = 'My Default';
		$this->assertEquals('My Default', Seo::description(''));
		$this->assertEquals('My Short Description', Seo::title('My Short Description'));
	}

	public function testDescription()
	{
		$this->assertEquals('My custom and...', Seo::description(static::LONG_STRING, 20));
		$this->assertEquals('My custom...', Seo::description(static::LONG_STRING, 10));
	}
}
