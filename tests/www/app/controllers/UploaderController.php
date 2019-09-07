<?php

namespace controllers;

use Win\Filesystem\Directory;
use Win\Filesystem\Files\Image;
use Win\Filesystem\Upload\TempFile;
use Win\Filesystem\Upload\Uploader;
use Win\FlashMessage\Alert;
use Win\Mvc\Controller;
use Win\Request\Input;

class UploaderController extends Controller
{
	public function index()
	{
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
