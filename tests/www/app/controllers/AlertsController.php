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
use Win\Filesystem\File;
use Win\Mail\Email;
use Win\Calendar\Timer;

class AlertsController extends Controller {

	protected function init() {
		
	}

	public function index() {
		Alert::error('Ops! Um erro');
		Alert::error('Outro erro');
		Alert::success('Parabéns');

		$this->redirect('alerts/show');
	}

	public function show() {
		return new View('alerts');
	}

}
