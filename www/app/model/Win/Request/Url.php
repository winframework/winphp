<?php

namespace Win\Request;

use Win\Singleton\SingletonTrait;

/**
 * Manipulador de URLs
 * 
 */
class Url {

	use SingletonTrait;

	protected $base = null;
	protected $url = null;
	protected $suffix = '/';
	protected $protocol = null;

	/**
	 * Define um novo sufixo de URL
	 * @param string $suffix
	 */
	public function setSuffix($suffix) {
		$this->suffix = $suffix;
	}

	/**
	 * Retorna no formato de URL
	 * @param string $url
	 * @return string
	 */
	public function format($url) {
		return rtrim($url, $this->suffix) . $this->suffix;
	}

	/**
	 * Redireciona para a URL escolhida
	 * @param string $url URL relativa ou absoluta
	 */
	public function redirect($url = '') {
		if (strpos($url, '://') === false) {
			$url = $this->getBaseUrl() . $url;
		}
		Header::instance()->set('location', $url);
	}

	/**
	 * Retorna a URL base
	 * @return string
	 */
	public function getBaseUrl() {
		if (is_null($this->base)) {
			$protocol = $this->getProtocol();
			$host = Input::server('HTTP_HOST');
			$script = Input::server('SCRIPT_NAME');
			$basePath = preg_replace('@/+$@', '', dirname($script)) . '/';
			$this->base = $protocol . '://' . $host . $basePath;
		}
		return $this->base;
	}

	/**
	 * Retorna o protocolo atual
	 * @return string (http|https)
	 */
	public function getProtocol() {
		if (is_null($this->protocol)) {
			$this->protocol = Input::protocol();
		}
		return $this->protocol;
	}

	/**
	 * Retorna a URL atual
	 * @return string
	 */
	public function getUrl() {
		if (is_null($this->url)) {
			$host = Input::server('HTTP_HOST');
			$url = '';
			if ($host) {
				$requestUri = explode('?', Input::server('REQUEST_URI'));
				$context = explode($host, $this->getBaseUrl());
				$uri = (explode(end($context), $requestUri[0], 2));
				$url = end($uri);
			}
			$this->url = $this->format($url);
		}
		return $this->url;
	}

	/**
	 * @param string $url
	 */
	public function setUrl($url) {
		$this->url = $this->format($url . '/');
	}

	/**
	 * Retorna o array de fragmentos da URL
	 * @return string[]
	 */
	public function getSegments() {
		$url = rtrim($this->getUrl(), $this->suffix);
		return explode('/', $url);
	}

}
