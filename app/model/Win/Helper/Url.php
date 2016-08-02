<?php

namespace Win\Helper;

use Win\Request\Input;
use Win\DesignPattern\Singleton;

/**
 * Gerenciador de URL
 * 
 * Auxilia no gerenciamento de URLs
 */
class Url extends Singleton {

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
	 * Adicionando base e sufixo se necessário
	 * @param string $url URL sem barra no final
	 * @return string
	 */
	public function format($url) {
		$url = rtrim($url, $this->sufix) . $this->sufix;
		return $url;
	}

	/**
	 * Redireciona para a url escolhida
	 * @param string $url
	 */
	public function redirect($url = '') {
		header('location:' . $this->getBaseUrl() . $this->format($url));
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
				$requestUri = Input::server('REQUEST_URI');
				$context = explode($host, $this->getBaseUrl());
				$uri = (explode(end($context), $requestUri, 2));
				$url = end($uri);
			endif;
			$this->url = $this->format($url);
		endif;
		return $this->url;
	}

	/**
	 * Usada apenas para testes
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
