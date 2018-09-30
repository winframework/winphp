<?php

namespace controller;

use Win\File\Directory;
use Win\File\Type\Image;
use Win\File\Upload\TempFile;
use Win\File\Upload\Uploader;
use Win\Message\Alert;
use Win\Mvc\Controller;
use Win\Mvc\View;

class ExemploController extends Controller {

	protected function init() {
		
	}

	public function index() {
		return new View('exemplo/index');
	}

	public function alert() {
		Alert::error('Ops! Um erro');
		Alert::error('Outro erro');
		Alert::success('Parabéns');

		$this->redirect('exemplo/show-alert');
	}

	public function showAlert() {
		return new View('exemplo/show-alert');
	}

	public function uploader() {

		$image = null;
		if (isset($_POST['submit'])) {
			$dir = new Directory('data/upload');
			$dir->delete();
			$uploader = new Uploader($dir);

			$uploader->prepare(TempFile::fromFiles('upload'));
			$success = $uploader->upload('my-file');
			if ($success) {
				Alert::success('Imagem salva');
			} else {
				Alert::error('Imagem com erro');
			}

			$image = new Image($uploader->getUploaded()->getPath());
		}

		$this->addData('image', $image);
	}

}
