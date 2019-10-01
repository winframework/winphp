<?php

namespace App\Controllers;

use Win\Controllers\Controller;
use Win\Models\Filesystem\Directory;
use Win\Models\Filesystem\Image;
use Win\Models\Filesystem\TempFile;
use Win\Repositories\Alert;
use Win\Repositories\Filesystem\Uploader;
use Win\Request\Input;
use Win\Views\View;

class UploaderController extends Controller
{
	public $image = null;

	public function index()
	{
		if (!is_null(Input::post('submit'))) {
			$dir = new Directory('data/uploads');
			$dir->delete();
			$uploader = new Uploader($dir);

			$uploader->prepare(TempFile::fromFiles('upload'));
			$success = $uploader->upload('my-file');
			if ($success) {
				Alert::success('Imagem salva');
				$this->image = new Image($uploader->getUploaded()->getPath());
			} else {
				Alert::error('Imagem com erro');
			}
		}

		return new View('uploader');
	}
}
