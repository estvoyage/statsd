<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world
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

	function send($value, world\connection $connection, $timeout = null)
	{
		$connection->send($this->value . ':' . $value, $timeout);

		return $this;
	}
}
