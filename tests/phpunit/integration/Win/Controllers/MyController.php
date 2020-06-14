<?php

namespace Win\Controllers;

use App\Controllers\BasicController;

class MyController extends BasicController
{
	private $a;
	private $b;

	public function __construct($a = null)
	{
		$this->a =  $a;
	}

	public function getA()
	{
		return $this->a;
	}

	public function getB()
	{
		return $this->b;
	}

	public function sum($first, $second)
	{
		return $first + $second;
	}
}
