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
		$type
	;

	function __construct($value, $type)
	{
		$this->value = $value;
		$this->type = $type;
	}

	function send(statsd\bucket $bucket, statsd\connection $connection, statsd\value\sampling $sampling = null, statsd\connection\socket\timeout $timeout = null)
	{
		$bucket->send($data = $this->value . '|' . $this->type, $connection, $sampling ?: new value\sampling, $timeout);

		return $this;
	}
}
