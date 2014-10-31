<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd
;

class bucket implements world\bucket
{
	private
		$value
	;

	function __construct($value)
	{
		$this->value = $value;
	}

	function writeOn(statsd\connection $connection)
	{
		return $connection
			->startPacket()
				->startMetric()
					->write($this->value . ':')
		;
	}
}
