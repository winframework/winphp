<?php

namespace App\Controllers;

use Win\Controllers\Controller;
use Win\Repositories\Filesystem\Image;
use Win\Services\Alert;
use Win\Repositories\Filesystem;
use Win\Repositories\Filesystem\Uploader;
use Win\Request\Input;
use Win\Views\View;

class UploaderController extends Controller
{
	const UPLOAD_PATH = 'data/uploads';

	public ?Image $image = null;

	private FileSystem $fs;
	private Uploader $uploader;

	public function __construct(Filesystem $fs, Uploader $uploader)
	{
		$this->fs = $fs;
		$this->uploader = $uploader;
	}

	public function init()
	{
		$this->fs->delete(static::UPLOAD_PATH);
		$this->fs->create(static::UPLOAD_PATH);
	}

	public function index()
	{
		if (Input::post('submit')) {
			try {
				$this->uploader->prepare(Input::file('upload'));
				$file = $this->uploader->upload(static::UPLOAD_PATH);

				Alert::success('Imagem salva com sucesso.');
				$this->image = new Image($file->getPath());
			} catch (\Exception $e) {
				Alert::error('Ocorreu um erro ao enviar a Imagem.');
			}
		}

		return new View('uploader');
	}
}
