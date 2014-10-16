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

	function send($value, world\connection $connection, statsd\value\sampling $sampling, statsd\connection\socket\timeout $timeout = null)
	{
		$sampling->send($this->value . ':' . $value, $connection, $timeout);

		return $this;
	}
}
