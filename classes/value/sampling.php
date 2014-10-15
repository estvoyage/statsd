<?php

namespace estvoyage\statsd\value;

use
	estvoyage\statsd\world as statsd
;

class sampling implements statsd\value\sampling
{
	private
		$value
	;

	function __construct($value = 1)
	{
		if (filter_var($value, FILTER_VALIDATE_FLOAT) === false || $value <= 0)
		{
			throw new sampling\exception('Sampling must be a float greater than 0.0');
		}

		$this->value = $value;
	}

	function applyTo(statsd\value $value, callable $callback)
	{
		$value->applySampling($this->value, $callback);

		return $this;
	}
}
