<?php

namespace Win\Request;

class CustomUrl extends Url
{
	public static $fakeSegments = [];

	public function getSegments()
	{
		return static::$fakeSegments;
	}
}
