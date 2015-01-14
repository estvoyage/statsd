<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\statsd
;

final class counting extends statsd\metric
{
	function __construct($bucket, $value, $sampling = null)
	{
		parent::__construct(new statsd\bucket($bucket), new statsd\value($value), statsd\value\type\counting::build(), $sampling === null ? $sampling : new statsd\value\sampling($sampling));
	}
}
