<?php

namespace estvoyage\statsd\world;

use
	estvoyage\statsd\metric
;

interface probe
{
	function bucketIs(metric\bucket $bucket);
}
