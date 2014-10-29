<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd
;

class metric implements statsd\metric
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

	function writeOn(statsd\connection $connection, callable $callback = null)
	{
		$connection
			->writeData($this->bucket, function($connection) use ($callback) {
					$connection
						->writeData($this->value, function($connection) use ($callback) {
								if ($callback)
								{
									$callback($connection);
								}
							}
						)
					;
				}
			)
		;

		return $this;
	}
}
