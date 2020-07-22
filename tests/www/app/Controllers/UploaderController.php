<?php

namespace App\Controllers;

use Win\Controllers\Controller;
use Win\Repositories\Filesystem\Image;
use Win\Services\Alert;
use Win\Repositories\Filesystem;
use Win\Request\Input;
use Win\Views\View;

class UploaderController extends Controller
{
	const UPLOAD_PATH = 'data/uploads';

	public ?Image $image = null;

	private FileSystem $fs;

	public function __construct(Filesystem $fs)
	{
		$this->fs = $fs;
	}
	
	public function index()
	{
		$this->fs->delete(static::UPLOAD_PATH);
		if (Input::post('submit')) {
			try {
				$this->fs->receiveFile(Input::file('upload'));
				$file = $this->fs->upload(static::UPLOAD_PATH);

				Alert::success('Imagem salva com sucesso.');
				$this->image = new Image($file->getPath());
			} catch (\Exception $e) {
				Alert::error($e);
			}
		}

		return new View('uploader/index');
	}
}
