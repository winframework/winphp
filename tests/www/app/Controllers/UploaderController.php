<?php

namespace App\Controllers;

use Exception;
use Win\Utils\Input;
use Win\Controllers\Controller;
use Win\Services\Filesystem;
use Win\Services\Alert;
use Win\Templates\View;

class UploaderController extends Controller
{
	const UPLOAD_PATH = 'data/uploads';

	private FileSystem $fs;
	public string $image = '';

	public function __construct(Filesystem $fs)
	{
		$this->fs = $fs;
	}

	public function index()
	{
		if (Input::post('submit')) {
			try {
				$this->fs->receiveFile(Input::file('upload'));
				$file = $this->fs->upload(static::UPLOAD_PATH);

				Alert::success('Imagem salva com sucesso.');
				$this->image = static::UPLOAD_PATH . '/' . $file;
			} catch (Exception $e) {
				Alert::error($e);
			}
		}

		return new View('uploader/index');
	}
}
