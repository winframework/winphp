<?php

namespace App\Controllers;

use Exception;
use Win\Common\Utils\Input;
use Win\Controllers\Controller;
use Win\Services\Filesystem;
use Win\Services\Filesystem\Image;
use Win\Services\Alert;
use Win\Templates\View;

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
				$this->image = new Image(static::UPLOAD_PATH . '/' . $file);
			} catch (Exception $e) {
				Alert::error($e);
			}
		}

		return new View('uploader/index');
	}
}
