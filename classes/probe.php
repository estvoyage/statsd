<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd,
	estvoyage\statsd\metric
;

interface probe extends metric\provider
{
	function newStatsdBucket(metric\bucket $bucket);
}
