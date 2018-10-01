<?php

namespace Win\Format;

use PHPUnit_Framework_TestCase;

class StrTest extends PHPUnit_Framework_TestCase {

	const LOREM = 'Lôrem ipsum dolor sit amét, consectetur adipiscing elit.'
			. 'Cras euismod a erat ac faucibus. Vivamus faucibus malesuada est, eget '
			. 'ullamcorper leo fringilla viverra.';

	public function testToUrl() {
		$url = Str::toUrl('Mi.nh@-+=$tring]!/ c0m₂² A"©&ntuaçãoβ ');
		$this->assertEquals('minh-tring-c0m22-acntuacao', $url);
	}

	public function testTruncate_Equals() {
		$truncated = Str::truncate('Minhâ stríng curta', 20);
		$this->assertEquals('Minhâ stríng curta', $truncated);
	}

	public function testTruncate_Before() {
		$truncated = Str::truncate(static::LOREM, 30);
		$this->assertEquals('Lôrem ipsum dolor sit amét...', $truncated);
	}

	public function testTruncate_After() {
		$truncated = Str::truncate(static::LOREM, 30, true);
		$this->assertEquals('Lôrem ipsum dolor sit amét, consectetur...', $truncated);
	}

	public function testToCamel() {
		$string = Str::toCamel('Lorem ipsum dolor sit amet');
		$this->assertEquals('loremIpsumDolorSitAmet', $string);
	}

	public function testToCamel_lower() {
		$string = Str::toCamel('lorem ipsum dolor sit amet');
		$this->assertEquals('loremIpsumDolorSitAmet', $string);
	}

	public function testToCamel_upper() {
		$string = Str::toCamel('LOREM IPSUM DOLOR SIT AMET');
		$this->assertEquals('loremIpsumDolorSitAmet', $string);
	}

}
