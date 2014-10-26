<?php

namespace estvoyage\statsd\packet;

use
	estvoyage\statsd
;

class timing extends statsd\packet
{
	function __construct($bucket, $value, $sampling = null)
	{
		parent::__construct(new statsd\bucket($bucket), new statsd\value\timing($value, new statsd\value\sampling($sampling)));
	}
}
