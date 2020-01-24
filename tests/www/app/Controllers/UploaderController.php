<?php

namespace App\Controllers;

use Win\Controllers\Controller;
use Win\Models\Filesystem\Image;
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
		if (!is_null(Input::post('submit'))) {
			$uploader = new Uploader(static::UPLOAD_PATH);

			$uploader->prepare(Input::file('upload'));
			$uploader->upload();

			try {
				Alert::success('Imagem salva');
				$this->image = new Image($uploader->getUploaded()->getPath());
			} catch (\Exception $e) {
				Alert::error('Imagem com erro');
			}
		}

		return new View('uploader');
	}
}
