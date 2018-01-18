<?php

/*
 * FUNÇÕES DE TRATAMENTO DE STRINGS
 * Funções que tratam uma string e a retorna modificada começam com nome: str[...]
 * Funções que convertem uma string para outro formato começam com nome: strTo[...]
 * Funções de conversão deve ter o nome no formato: [...]To[...]
 */

setlocale(LC_ALL, 'pt_BR.UTF8');

/**
 * Corta um texto, sem cortar a última palavra.
 * @param string $string [string a ser cortada]
 * @param int $length [tamanho da string cortada]
 * @param bool $rep [define se corta antes ou depois do tamanho maximo]
 * @return string $string [string resumida ]
 */
function strTruncate($string, $length, $rep = false) {
	if (strlen($string) <= $length) {
		return $string;
	}

	if ($rep == true) {
		$oc = strrpos(substr($string, 0, $length), ' ');
	}
	if ($rep == false) {
		$oc = strpos(substr($string, $length), ' ') + $length;
	}

	$string = substr($string, 0, $oc) . '...';
	return $string;
}

/**
 * Limpa a string de caracteres inválidos
 * @param string $string
 * @return string
 */
function strClear($string) {
	return trim(strip_tags(str_replace('"', '&quot;', $string)));
}

/**
 * Criptografa a string utilizando MD5 + SALT
 * @param string $string [string a ser criptografada]
 * @return string $stringEncript [string criptografada]
 */
function strEncrypt($string, $salt = APP_ID) {
	return md5($salt . $string);
}

/**
 * Retorna total de caracteres da string UTF-8
 * @param string
 * @return int
 */
function strLength($string) {
	return mb_strlen($string);
}

/**
 * Retorna a string Maiúscula UTF-8
 * @param string $string
 * @return string
 */
function strUpper($string) {
	return mb_strtoupper($string);
}

/**
 * Retorna a string Minúscula UTF-8
 * @param string $string
 * @return string
 */
function strLower($string) {
	return mb_strtolower($string);
}

/**
 * Retorna a string somente com a primeira letra maiúscula UTF-8
 * @param string $string
 * @return string
 */
function strFirst($string) {
	return mb_strtoupper(mb_substr($string, 0, 1)) . mb_strtolower(mb_substr($string, 1));
}

/**
 * Limpa a string, retirando espaços e tags html
 * @param string $string
 * @return string [retorna string limpa]
 */
function strStrip($string) {
	return trim(strip_tags($string));
}

/**
 * Formata o número com zeros à esquerda
 * @param int $int [numero a ser formatado]
 * @param int $length [quantidade de caracteres que o numero deverá ter]
 * @return string [número formatado. Ex: 01, 02 , 001, 003]
 */
function strLengthFormat($int = 0, $length = 2) {
	return str_pad($int, $length, "0", STR_PAD_LEFT);
}

/**
 * Converte uma string, nome ou frase em URL válida
 * @param string $string [Ex: Minha Notícia da 'Página 2000 especial' ]
 * @return string $url [Ex: minha-noticia-da-pagina-2000-especial ]
 */
function strToURL($string) {
	$url = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
	$url = preg_replace("/[^a-zA-Z0-9\/_| -]/", '', $url);
	$url = strtolower(trim($url, '-'));
	$url = preg_replace("/[\/_| -]+/", '-', $url);
	return $url;
}

/**
 * Converte String para Float
 * @param string $string
 * @return float
 */
function strToFloat($string) {
	$f = str_replace(array('.', 'R$', '%'), array('', '', ''), $string);
	return (float) str_replace(',', '.', $f);
}

/**
 * Converte float para Moeda (em Real R$)
 * @param float $float
 * @return string
 */
function floatToMoney($float) {
	return number_format((float) $float, 2, ',', '.');
}

/**
 * Converte boolean para String 
 * @param boolean $boolean
 * @return string (Sim/Não)
 */
function booleanToString($boolean) {
	return ($boolean) ? 'Sim' : 'Não';
}

/**
 * Retorna no formato de ID, ex: #00009 
 * @param int
 * @return string
 */
function formatId($id = 0) {
	return '#' . strLengthFormat($id, 6);
}
