<?php

namespace Win\File;

class StrTest extends \PHPUnit_Framework_TestCase {

	public function testoValidName() {
		$this->assertEquals('produtos-de-otima-qualidade-2', Str::toValidName('.Produtos-de_óti?ma q.ualida@"de/2-'));
	}

}
