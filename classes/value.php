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

	function __construct($value, $type, statsd\value\sampling $sampling = null)
	{
		$this->value = $value;
		$this->type = $type;

		$sampling = $sampling ?: new value\sampling;

		$sampling->applyTo($this, function($value) {
				$this->sampling = $value->sampling;
			}
		);
	}

	function applySampling($sampling, callable $callback)
	{
		$value = clone $this;
		$value->sampling = $sampling;

		$callback($value);

		return $this;
	}

	function send(statsd\bucket $bucket, statsd\connection $connection, $timeout = null)
	{
		$bucket->send($this->value, $this->type, $this->sampling, $connection, $timeout);

		return $this;
	}
}
