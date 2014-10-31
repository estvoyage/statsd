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

	function writeOn(statsd\connection $connection)
	{
		return $connection
			->writeData($this->bucket)
				->writeData($this->value)
		;
	}
}
