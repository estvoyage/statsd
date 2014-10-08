<?php

namespace seshat\statsd;

use
	seshat\statsd\world as statsd
;

class value implements statsd\value
{
	private
		$value,
		$type,
		$sampleRate
	;

	function __construct($value, $type, $sampleRate = 1)
	{
		$this->value = $value;
		$this->type = $type;
		$this->sampleRate = $sampleRate;
	}

	function send(statsd\bucket $bucket, statsd\connection $connection, $timeout = null)
	{
		$bucket->send($this->value, $this->type, $this->sampleRate, $connection, $timeout);

		return $this;
	}
}
