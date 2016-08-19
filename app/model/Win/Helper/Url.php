<?php

namespace Win\Helper;

use Win\Request\Input;

/**
 * Gerenciador de URL
 * 
 */
class Url {

	use \Win\DesignPattern\Singleton;

	protected $base = null;
	protected $url = null;
	protected $sufix = '/';
	protected $protocol = null;

	/**
	 * Define um novo sufixo de URL
	 * @param string $sufix
	 */
	public function setSufix($sufix) {
		$this->sufix = $sufix;
	}

	/**
	 * Retorna no formato de URL
	 * @param string $url
	 * @return string
	 */
	public function format($url) {
		$url = rtrim($url, $this->sufix) . $this->sufix;
		return $url;
	}

	/**
	 * Redireciona para a URL escolhida
	 * @param string $url Url relativa ou absoluta
	 */
	public function redirect($url = '') {
		if (strpos($url, '://') === false) {
			$url = $this->getBaseUrl() . $url;
		}
		header('location:' . $this->format($url));
		die();
	}

	/**
	 * Retorna a URL base
	 * @return string
	 */
	public function getBaseUrl() {
		if (is_null($this->base)):
			$protocol = $this->getProtocol();
			$host = Input::server('HTTP_HOST');
			$script = Input::server('SCRIPT_NAME');
			$basePath = preg_replace('@/+$@', '', dirname($script)) . '/';
			$this->base = $protocol . '://' . $host . $basePath;
		endif;
		return $this->base;
	}

	/**
	 * Retorna o protocolo atual
	 * @return string (http|https)
	 */
	public function getProtocol() {
		if (is_null($this->protocol)):
			$this->protocol = Input::protocol();
		endif;
		return $this->protocol;
	}

	/**
	 * Retorna a URL atual
	 * @return string
	 */
	public function getUrl() {
		if (is_null($this->url)):
			$host = Input::server('HTTP_HOST');
			$url = '';
			if ($host):
				$requestUri = explode('?', Input::server('REQUEST_URI'));
				$context = explode($host, $this->getBaseUrl());
				$uri = (explode(end($context), $requestUri[0], 2));
				$url = end($uri);
			endif;
			$this->url = $this->format($url);
		endif;
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
	public function getFragments() {
		$url = rtrim($this->getUrl(), $this->sufix);
		return explode('/', $url);
	}

}
