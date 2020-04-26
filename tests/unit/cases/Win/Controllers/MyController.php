<?php

namespace Win\Controllers;

class MyController extends Controller
{
	public function __construct($a = null)
	{
		$this->addData('a', $a);
	}

	public function getA()
	{
		return $this->getData('a');
	}

	public function getB()
	{
		return $this->getData('b');
	}

	public function sum($first, $second)
	{
		return $first + $second;
	}
}
