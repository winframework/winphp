<?php

namespace Win\Alert;

class AlertInfo extends Alert {

	public function __construct($message) {
		parent::__construct('info', $message);
	}

}
