<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\statsd
;

final class set extends statsd\metric
{
	function __construct($bucket, $value)
	{
		parent::__construct(new statsd\bucket($bucket), new statsd\value($value), statsd\value\type\set::build());
	}
}
