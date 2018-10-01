<?php

namespace Win\Format;

use PHPUnit_Framework_TestCase;

class StrTest extends PHPUnit_Framework_TestCase {

	const LOREM = 'Lôrém ípsüm dolor sit amét, consectetur adipiscing elit.'
			. 'Cras euismod a erat ac faucibus. Vivamus faucibus malesuada est, eget '
			. 'ullamcorper leo fringilla viverra.';

	public function testToUrl() {
		$url = Str::toUrl('Mi.nh@-+=$tring]!/ c0m₂² A"©&ntuaçãoβ ');
		$this->assertEquals('minh-tring-c0m22-acntuacao', $url);
	}

	public function testToFileName() {
		$this->assertEquals('produtos-de-otima-qualidade-2', Str::toFileName('.Produtos-de_óti?ma q.ualida@"de/²-'));
	}

	public function testTruncate_Equals() {
		$truncated = Str::truncate('Minhâ stríng curta', 20);
		$this->assertEquals('Minhâ stríng curta', $truncated);
	}

	public function testTruncate_WhiteSpace() {
		$this->assertEquals('1inha string...', Str::truncate('1inha string curta', 13));
		$this->assertEquals('2inha string...', Str::truncate('2inha string curta', 12));
		$this->assertEquals('3inha...', Str::truncate('3inha string curta', 11));
	}

	public function testTruncate_Simbol() {
		$this->assertEquals('1inha string...', Str::truncate('1inha string, curta', 14));
		$this->assertEquals('1inha string...', Str::truncate('1inha string, curta', 13));
		$this->assertEquals('1inha...', Str::truncate('1inha string, curta', 12));
		$this->assertEquals('2inha string...', Str::truncate('2inha string , curta', 14));
	}

	public function testTruncate_Special() {
		$this->assertEquals('Lôrém ípsüm...', Str::truncate(static::LOREM, 14));
		$this->assertEquals('Lôrém ípsüm...', Str::truncate(static::LOREM, 13));
		$this->assertEquals('Lôrém ípsüm...', Str::truncate(static::LOREM, 12));
		$this->assertEquals('Lôrém ípsüm...', Str::truncate(static::LOREM, 11));
		$this->assertEquals('Lôrém...', Str::truncate(static::LOREM, 10));
	}

	public function testTruncate_After() {
		$truncated = Str::truncate(static::LOREM, 30, true);
		$this->assertEquals('Lôrém ípsüm dolor sit amét, consectetur...', $truncated);
	}

	public function testLower() {
		$string = Str::lower('Lôrém ípsüm dolor sit AMÉT');
		$this->assertEquals('lôrém ípsüm dolor sit amét', $string);
	}

	public function testLength() {
		$length = Str::length('Lôrém ípsüm dolor sit AMÉT');
		$this->assertEquals(26, $length);
	}

	public function testUpper() {
		$string = Str::upper('LÔrem ipsum dolor sit AMÉT');
		$this->assertEquals('LÔREM IPSUM DOLOR SIT AMÉT', $string);
	}

	public function testCamel() {
		$string = Str::camel('Lorem ipsum dolor sit amet');
		$this->assertEquals('loremIpsumDolorSitAmet', $string);
	}

	public function testCamel_lower() {
		$string = Str::camel('lorem ipsum dolor sit amet');
		$this->assertEquals('loremIpsumDolorSitAmet', $string);
	}

	public function testCamel_upper() {
		$string = Str::camel('LOREM IPSUM DOLOR SIT AMET');
		$this->assertEquals('loremIpsumDolorSitAmet', $string);
	}

	public function testStrip() {
		$string = Str::strip(' LOREM <b>IPSUM</b> DOLOR     ');
		$this->assertEquals('LOREM IPSUM DOLOR', $string);
	}

	public function testZeroOnLeft() {
		$this->assertEquals('0095', Str::zeroOnLeft(95, 4));
		$this->assertEquals('96', Str::zeroOnLeft(96, 2));
		$this->assertEquals('97', Str::zeroOnLeft(97, 1));
	}

}
