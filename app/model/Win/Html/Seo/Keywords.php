<?php

namespace Win\Html\Seo;

use Win\Mvc\Application;

/**
 * Auxilia gerenciamento de keywords
 */
class Keywords {

	const MAX_LENGTH = 100;

	/**
	 * Retorna uma string em minuscula, separada por virgula
	 * @param string[]
	 */
	static function toKeys($array1, $array2 = []) {
		if (empty($array2)) {
			$array2 = [Application::app()->controller->getData('keywords')];
		}
		return strLower(str_replace([',...', '...'], '', strTruncate(implode(', ', array_filter(array_merge($array1, $array2))), static::MAX_LENGTH)));
	}

}
