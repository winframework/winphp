<?php

namespace App\Controllers;

use Win\Controllers\Controller;
use Win\Repositories\Filesystem\Image;
use Win\Repositories\Alert;
use Win\Repositories\Filesystem;
use Win\Repositories\Filesystem\Uploader;
use Win\Request\Input;
use Win\Views\View;

class UploaderController extends Controller
{
	const UPLOAD_PATH = 'data/uploads';

	/** @var Image */
	public $image = null;

	/** @var FileSystem */
	private $fs;

	public function __construct()
	{
		$fs = new Filesystem();
		$fs->delete(static::UPLOAD_PATH);
		$fs->create(static::UPLOAD_PATH);

		$this->fs = $fs;
	}

	public function index()
	{
		if (Input::post('submit')) {
			try {
				$uploader = new Uploader(static::UPLOAD_PATH);

				$uploader->prepare(Input::file('upload'));
				$file = $uploader->upload();

				Alert::success('Imagem salva com sucesso.');
				$this->image = new Image($file->getPath());
			} catch (\Exception $e) {
				Alert::error('Ocorreu um erro ao enviar a Imagem.');
			}
		}

		return new View('uploader');
	}
}
