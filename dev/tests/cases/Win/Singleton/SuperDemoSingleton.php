<?php

namespace Win\DesignPattern;

class SuperDemoSingleton {

	use SingletonTrait;

	public $value = 10;

	public function getMyClass() {
		return $this->getClassDi();
	}

}
