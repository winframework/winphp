<?php

namespace App\Views;

use Win\Views\View;

class ClassView extends View
{
	public $text = 'My custom text';
	/** @var int */
	protected $code;
	private $varPrivate = 10;

	public function __construct($code)
	{
		$this->code = $code;
		parent::__construct('class-view');
	}

	public function getCode()
	{
		return $this->code;
	}
}
