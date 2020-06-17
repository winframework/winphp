<?php

namespace Win\Common\Utils;

use PHPUnit\Framework\TestCase;

class StrTest extends TestCase
{
	const LOREM = 'Lôrém ípsüm dolor sit amét, consectetur adipiscing elit.'
			. 'Cras euismod a erat ac faucibus. Vivamus faucibus malesuada est, eget '
			. 'ullamcorper leo fringilla viverra.';

	public function testToUrl()
	{
		$url = Str::toUrl('Mi.nh@-+=$tring]!/ c0m₂² A"©&ntuaçãoβ ');
		$this->assertEquals('minh-tring-c0m22-a-cntuacao', $url);
	}

	public function testTruncateEquals()
	{
		$truncated = Str::truncate('Minhâ stríng curta', 20);
		$truncated2 = Str::truncate('Minhâ stríng curta', 20, Str::TRUNCATE_BEFORE);
		$this->assertEquals('Minhâ stríng curta', $truncated);
		$this->assertEquals('Minhâ stríng curta', $truncated2);
	}

	public function testTruncateWhiteSpace()
	{
		$this->assertEquals('1inha string...', Str::truncate('1inha string curta', 13));
		$this->assertEquals('2inha string...', Str::truncate('2inha string curta', 12));
		$this->assertEquals('3inha...', Str::truncate('3inha string curta', 11));
	}

	public function testTruncateSymbol()
	{
		$this->assertEquals('1inha string...', Str::truncate('1inha string, curta', 14));
		$this->assertEquals('1inha string...', Str::truncate('1inha string, curta', 13));
		$this->assertEquals('1inha...', Str::truncate('1inha string, curta', 12));
		$this->assertEquals('2inha string...', Str::truncate('2inha string , curta', 14));
	}

	public function testTruncateSpecial()
	{
		$this->assertEquals('Lôrém ípsüm...', Str::truncate(static::LOREM, 14));
		$this->assertEquals('Lôrém ípsüm...', Str::truncate(static::LOREM, 13));
		$this->assertEquals('Lôrém ípsüm...', Str::truncate(static::LOREM, 12));
		$this->assertEquals('Lôrém ípsüm...', Str::truncate(static::LOREM, 11));
		$this->assertEquals('Lôrém...', Str::truncate(static::LOREM, 10));
	}

	public function testTruncateHtml()
	{
		$string = 'Lôrém <b>ípsüm <span class="text-center">dolor</span>'
		. ' sit</b> amét';

		$this->assertEquals(
			'Lôrém ípsüm...',
			Str::truncate($string, 14)
		);
	}

	public function testTruncateAfter()
	{
		$truncated = Str::truncate(static::LOREM, 30, Str::TRUNCATE_AFTER);
		$this->assertEquals('Lôrém ípsüm dolor sit amét, consectetur...', $truncated);
	}

	public function testLower()
	{
		$string = Str::lower('Lôrém ípsüm dolor sit AMÉT');
		$this->assertEquals('lôrém ípsüm dolor sit amét', $string);
	}

	public function testLength()
	{
		$length = Str::length('Lôrém ípsüm dolor sit AMÉT');
		$this->assertEquals(26, $length);
	}

	public function testUpper()
	{
		$string = Str::upper('LÔrem ipsum dolor sit AMÉT');
		$this->assertEquals('LÔREM IPSUM DOLOR SIT AMÉT', $string);
	}

	public function testCamel()
	{
		$string = Str::camel('Lôrem ipsum dolor_sit-amet');
		$this->assertEquals('LremIpsumDolorSitAmet', $string);
	}

	public function testCamelLower()
	{
		$string = Str::camel('lorem ipsum dolor sit amet');
		$this->assertEquals('LoremIpsumDolorSitAmet', $string);
	}

	public function testCamelUpper()
	{
		$string = Str::camel('LOREM IPSUM DOLOR SIT AMET');
		$this->assertEquals('LoremIpsumDolorSitAmet', $string);
	}

	public function testLowerCamel()
	{
		$string = Str::lowerCamel('_Lôrem ipsum dolor_sit-amet');
		$this->assertEquals('_lremIpsumDolorSitAmet', $string);
		$this->assertEquals('__callStatic', Str::lowerCamel('__Call sTatic'));
		$this->assertEquals('lremIpsum', Str::lowerCamel('Lôrem ipsum'));
	}

	public function testStrip()
	{
		$string = Str::strip(' LOREM <b>IPSUM</b> DOLOR     ');
		$this->assertEquals('LOREM IPSUM DOLOR', $string);
	}
}
