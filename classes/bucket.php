<?php

namespace seshat\statsd;

use
	seshat\statsd\world
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

	function send($value, $type, $sampleRate, world\connection $connection, $timeout = null)
	{
		$connection->send($this->value, $value, $type, $sampleRate, $timeout);

		return $this;
	}
}
