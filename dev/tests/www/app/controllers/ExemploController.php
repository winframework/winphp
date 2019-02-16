<?php

namespace controllers;

use Win\Filesystem\Directory;
use Win\Filesystem\Image;
use Win\Filesystem\Upload\TempFile;
use Win\Filesystem\Upload\Uploader;
use Win\FlashMessage\Alert;
use Win\Mvc\Controller;
use Win\Mvc\View;
use Win\Request\Input;

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
		
		if (!is_null(Input::post('submit'))) {
			$dir = new Directory('data/uploads');
			$dir->delete();
			$uploader = new Uploader($dir);

			$uploader->prepare(TempFile::fromFiles('upload'));
			$success = $uploader->upload('my-file');
			if ($success) {
				Alert::success('Imagem salva');
				$image = new Image($uploader->getUploaded()->getPath());
			} else {
				Alert::error('Imagem com erro');
			}
		}

		$this->addData('image', $image);
	}

}
