<?php

namespace controller;

use Win\File\Directory;
use Win\File\TempFile;
use Win\File\Uploader;
use Win\Mvc\Controller;
use Win\Mvc\View;

class ExemploController extends Controller {

	protected function init() {
		
	}

	public function index() {

		if (isset($_POST['submit'])) {
			$dir = new Directory('data/upload');
			$uploader = new Uploader($dir);


			$uploader->prepare(new TempFile($_FILES['upload']));
			$success = $uploader->genarateName()->upload();
			var_dump($success);
		}
		return new View('exemplo');
	}

}
