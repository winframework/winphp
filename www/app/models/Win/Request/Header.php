<?php

namespace Win\Request;

use Exception;
use Win\Formats\Arr\Data;

/**
 * Header HTTP
 */
class Header extends Data
{
	/**
	 * Enviar por 'HTTP Header' os valores que foram incluídos no Header
	 * @throws Exception
	 * @codeCoverageIgnore
	 */
	public function run()
	{
		foreach ($this->all() as $key => $value) {
			header($key . ':' . $value);
		}
		if ($this->get('location')) {
			throw new Exception('Redirect fail!');
		}
	}
}
