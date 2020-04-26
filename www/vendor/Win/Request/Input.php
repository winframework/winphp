<?php

namespace Win\Request;

/**
 * Manipulador de variáveis globais ($_REQUEST, $_POST, $_GET, etc)
 *
 * Fornecendo uma camada de segurança maior do que manipulá-las diretamente.
 */
class Input
{
	/**
	 * Retorna variável $_POST
	 * @param string $name
	 * @param int $filter
	 * @param mixed $default
	 * @return mixed
	 */
	public static function post($name, $filter = FILTER_SANITIZE_STRING, $default = null)
	{
		$post = filter_input(INPUT_POST, $name, $filter);

		return !is_null($post) ? $post : $default;
	}

	/**
	 * Retorna variável $_POST em modo array
	 * @param string $name
	 * @param int $filter
	 * @return mixed[]
	 */
	public static function postArray($name = null, $filter = FILTER_SANITIZE_STRING)
	{
		return (array) filter_input(INPUT_POST, $name, $filter, FILTER_REQUIRE_ARRAY);
	}

	/**
	 * Retorna variável $_SERVER
	 * @param string $name
	 * @param int $filter
	 * @return mixed
	 */
	public static function server($name, $filter = FILTER_DEFAULT)
	{
		$server = (key_exists($name, $_SERVER)) ? $_SERVER[$name] : '';

		return filter_var($server, $filter);
	}

	/**
	 * Retorna variável $_GET
	 * @param string $name
	 * @param int $filter
	 * @param mixed $default
	 * @return mixed
	 */
	public static function get($name, $filter = FILTER_SANITIZE_STRING, $default = null)
	{
		$get = filter_input(INPUT_GET, $name, $filter);

		return !is_null($get) ? $get : $default;
	}

	/**
	 * Retorna variável $_FILES
	 * @param string $name
	 * @return string[]
	 */
	public static function file($name)
	{
		if (key_exists($name, $_FILES)) {
			return $_FILES[$name];
		} else {
			return null;
		}
	}

	/**
	 * Retorna o protocolo atual
	 * @return string 'http'|'https'
	 */
	public static function protocol()
	{
		$https = Input::server('HTTPS');
		$port = Input::server('SERVER_PORT');
		if (!empty($https) && ('off' !== $https || 443 == $port)) {
			return 'https';
		}

		return 'http';
	}
}
