<?php

namespace Win\File\Upload;

use Win\File\Directory;

/**
 * Arquivos aceitos no upload
 */
interface UploadbleInterface {

	/** @param string $name */
	public function setName($name);

	/** @return string */
	public function getName();

	/** @return string */
	public function getExtension();

	/** @return string */
	public function getExtensionDot();

	/** @return boolean */
	public function move(Directory $directory);
}
