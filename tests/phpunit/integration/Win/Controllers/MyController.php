<?php

namespace Win\Controllers;

class MyController extends Controller
{
	private $a;
	private $b;

	public function __construct($a = null)
	{
		$this->a =  $a;
	}

	public function index()
	{
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
