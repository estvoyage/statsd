<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd
;

class packet implements statsd\packet
{
	private
		$bucket,
		$value
	;

	function __construct(statsd\bucket $bucket, statsd\value $value)
	{
		$this->bucket = $bucket;
		$this->value = $value;
	}

	function writeOn(statsd\connection $connection, callable $callback)
	{
		$this->bucket
			->writeOn($connection, function($connection) use ($callback) {
					$this->value
						->writeOn($connection, function($connection) use ($callback) {
								$callback($connection);
							}
						)
					;
				}
			)
		;

		return $this;
	}
}
