<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\statsd
;

final class timing extends statsd\metric
{
	function __construct($bucket, $value)
	{
		parent::__construct(new statsd\bucket($bucket), new statsd\value\timing($value));
	}
}
