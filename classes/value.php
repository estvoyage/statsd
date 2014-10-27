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
		$this->sampling = $sampling ?: new value\sampling;
	}

	function writeOn(statsd\connection $connection, callable $callback)
	{
		$connection
			->write($this->value . '|' . $this->type, function($connection) use ($callback) {
					$this->sampling
						->writeOn($connection, function($connection) use ($callback) {
								$connection->endMetric($callback);
							}
						)
					;
				}
			)
		;

		return $this;
	}
}
