<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\statsd
;

final class set extends statsd\metric
{
	function __construct(bucket $bucket, value $value)
	{
		parent::__construct($bucket, $value, new type('s'));
	}

	static function from($bucketAsString, $valueAsInteger)
	{
		return new self(bucket::ofName($bucketAsString), new value($valueAsInteger));
	}
}
