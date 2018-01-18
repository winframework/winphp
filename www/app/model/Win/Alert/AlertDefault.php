<?php

namespace Win\Alert;

class AlertDefault extends Alert {

	public function __construct($message) {
		parent::__construct('default', $message);
	}

}
