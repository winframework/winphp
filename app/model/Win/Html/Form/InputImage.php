<?php

/**
 * Input Imagem
 * Input type="file" personalizado, (apenas Imagens)
 * Esta classe irá adicionar um Plugin personalizado para Upload de Imagens
 *
 */

namespace Win\Html\Form;

use Win\File\Image;

class InputImage {

	public $inputName = '';
	public $thumbElement = '';
	public $currentImage = null;
	public $linkRemove = '';

	/**
	 * Gera o input file (que ficará oculto
	 * @param string $inputName name="" do INPUT
	 */
	public function getInput($inputName = '') {
		$this->inputName = $inputName;
		include 'public/plugins/jquery-file-upload/custom/input.phtml';
	}

	/**
	 * Gera os botões, barra de progresso, etc
	 * OBS: Lembre-se de chamar o "getPlugin()"
	 * @param Image $currentImage
	 * @param string $thumbElement Seletor jQuery da Imagem Atual
	 * @param string $linkRemove Link para excluir imagem
	 */
	public function getButtons($currentImage, $thumbElement = '#file_thumb', $linkRemove = '') {
		$this->currentImage = $currentImage;
		$this->thumbElement = $thumbElement;
		$this->linkRemove = $linkRemove;
		include 'public/plugins/jquery-file-upload/custom/buttons.phtml';
	}

	/**
	 * Inclui os arquivos javascript do plugin
	 * E define as funcionalidades
	 */
	public function getJs() {
		include 'public/plugins/jquery-file-upload/custom/javascript.phtml';
	}

}
