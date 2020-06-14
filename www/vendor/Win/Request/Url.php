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

	public static function init()
	{
		static::$protocol = Input::protocol();
		static::setBase();
		static::setPath();
		static::setSegments();
	}

	public static function full()
	{
		return static::$base . static::$path;
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
	protected static function setBase()
	{
		$host = Input::server('HTTP_HOST');
		$script = Input::server('SCRIPT_NAME');
		$basePath = preg_replace('@/+$@', '', dirname($script));
		static::$base = static::$protocol . '://' . $host . $basePath . '/';
	}

	/**
	 * Define o final da url
	 * @return string
	 */
	protected static function setPath()
	{
		$host = Input::server('HTTP_HOST');
		$path = '';
		if ($host) {
			$requestUri = explode('?', Input::server('REQUEST_URI'));
			$context = explode($host, static::$base);
			$uri = (explode(end($context), $requestUri[0], 2));
			$path = end($uri);
		}
		static::$path = $path;
	}

	/**
	 * Define os fragmentos da URL
	 */
	protected static function setSegments()
	{
		static::$segments = array_filter(explode('/', static::$path)) + static::HOME;
	}
}
