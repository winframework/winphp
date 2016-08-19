<?php

namespace Win\Alert;

class AlertError extends Alert {

	public function __construct($message) {
		parent::__construct('danger', $message);
	}

}
