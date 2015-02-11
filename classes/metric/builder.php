<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\statsd\metric
;

interface builder
{
	function bucketIs(metric\bucket $bucket);
}
