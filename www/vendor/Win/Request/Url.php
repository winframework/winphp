<?php

namespace Win\Request;

/**
 * Manipulador de URLs
 */
class Url
{
	const HOME = ['index', 'index'];
	const SUFFIX = '/';

	static $base;
	static $path;
	static $protocol;
	static $segments;
	static $full;

	public static function init()
	{
		static::$protocol = Input::protocol();
		static::$base = static::getBase();
		static::$path = static::getPath();
		static::$segments = static::getSegments();
		static::$full = static::$base . static::$path;
	}

	/**
	 * Retorna no formato de URL
	 * @param string $url
	 * @return string
	 */
	public static function format($url)
	{
		return rtrim($url, static::SUFFIX) . static::SUFFIX;
	}

	/**
	 * Redireciona para a URL escolhida
	 * @param string $url URL relativa ou absoluta
	 * @codeCoverageIgnore
	 */
	public static function redirect($url = '')
	{
		if (false === strpos($url, '://')) {
			$url = static::$base . $url;
		}
		header('location:' . $url);
		die();
	}

	/**
	 * Retorna a URL base
	 * @return string
	 */
	private static function getBase()
	{
		$host = Input::server('HTTP_HOST');
		if ($host) {
			$script = Input::server('SCRIPT_NAME');
			$basePath = preg_replace('@/+$@', '', dirname($script));
			return static::$protocol . '://' . $host . $basePath . '/';
		}
	}

	/**
	 * Define o final da URL
	 * @return string
	 */
	private static function getPath()
	{
		$host = Input::server('HTTP_HOST');
		if ($host) {
			$requestUri = explode('?', Input::server('REQUEST_URI'));
			$context = explode($host, static::$base);
			$uri = (explode(end($context), $requestUri[0], 2));
			return end($uri);
		}
	}

	/**
	 * Define os fragmentos da URL
	 * @return string[]
	 */
	private static function getSegments()
	{
		return array_filter(explode('/', static::$path)) + static::HOME;
	}
}
