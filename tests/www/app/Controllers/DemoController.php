<?php

namespace App\Controllers;

use App\Views\ClassView;
use Win\Controllers\Controller;
use Win\Views\View;

/**
 * Usado pelo PHPUnit
 */
class DemoController extends Controller
{
	public function __construct()
	{
		$this->ten = 10;
	}

	public function index()
	{
		$this->title = 'My Index Action';

		return new View('demo');
	}

	public function classView()
	{
		return new ClassView(10);
	}

	public function returnFive()
	{
		return 5;
	}

	public function returnValidView()
	{
		return new View('my-view');
	}

	public function viewSetValues()
	{
		return new View('my-view-values');
	}

	public function returnInvalidView()
	{
		return new View('this-file-not-exist');
	}

	public function returnInvalidView2()
	{
		return new View('my-view/invalid');
	}

	public function tryRefresh()
	{
		$this->refresh();
	}
}
