<?php

namespace Win\Database\Orm;

use PHPMailer\PHPMailer\Exception;

class OrmException extends Exception
{
	public function __toString()
	{
		return $this->getMessage() . ' [' . $this->getCode() . ']';
	}
}
