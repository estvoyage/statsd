<?php

namespace estvoyage\statsd\probe\memory;

use
	estvoyage\statsd,
	estvoyage\statsd\metric
;

final class peak extends statsd\probe
{
	function newBucket(metric\bucket $bucket)
	{
		return $this->newMetric(new metric\gauge($bucket, new metric\value(memory_get_peak_usage(true))));
	}
}
