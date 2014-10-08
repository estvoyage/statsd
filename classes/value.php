<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd
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
		if (filter_var($sampleRate, FILTER_VALIDATE_FLOAT) === false || $sampleRate <= 0)
		{
			throw new value\exception('Sample rate must be a float greater than 0.0');
		}

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
