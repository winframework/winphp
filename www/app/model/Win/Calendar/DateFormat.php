<?php

namespace Win\Calendar;

/**
 * Converte a data para formatos diferentes
 */
abstract class DateFormat {

	abstract public static function format(Date $date);
}
