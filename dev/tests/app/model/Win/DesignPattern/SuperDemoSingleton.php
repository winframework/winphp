<?php

namespace Win\DesignPattern;

class SuperDemoSingleton {

	use Singleton;

	public $value = 10;

	public function getMyClass() {
		return $this->getClassDi();
	}

}
