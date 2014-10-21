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

	function __construct($value = null)
	{
		if ($value !== null && (filter_var($value, FILTER_VALIDATE_FLOAT) === false || $value <= 0))
		{
			throw new sampling\exception('Sampling must be a float greater than 0.0');
		}

		$this->value = $value ?: 1;
	}

	function writeOn(statsd\connection $connection, callable $callback)
	{
		if ($this->value != 1)
		{
			$connection->write('|@' . $this->value, $callback);
		}

		return $this;
	}
}
