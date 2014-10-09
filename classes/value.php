<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\value,
	estvoyage\statsd\world as statsd
;

class value implements statsd\value
{
	private
		$value,
		$type,
		$sampling
	;

	function __construct($value, $type, $samplingValue = 1)
	{
		$this->sampling = new value\sampling($samplingValue);
		$this->value = $value;
		$this->type = $type;
	}

	function send(statsd\bucket $bucket, statsd\connection $connection, $timeout = null)
	{
		$bucket->sendWithSampling($this->value, $this->type, $this->sampling, $connection, $timeout);

		return $this;
	}
}
