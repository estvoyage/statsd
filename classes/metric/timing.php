<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\statsd
;

class timing extends statsd\metric
{
	function __construct($bucket, $value, $sampling = null)
	{
		parent::__construct(new statsd\bucket($bucket), new statsd\value\timing($value, new statsd\value\sampling($sampling)));
	}
}
