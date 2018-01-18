<?php

namespace Win\Alert;

class AlertSuccess extends Alert {

	public function __construct($message) {
		parent::__construct('success', $message);
	}

}
