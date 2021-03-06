<?php

namespace estvoyage\statsd\probe\memory;

use
	estvoyage\statsd\probe,
	estvoyage\statsd\metric
;

final class peak extends probe\generic
{
	function newStatsdBucket(metric\bucket $bucket)
	{
		return $this->newStatsdMetric(new metric\gauge($bucket, new metric\value(memory_get_peak_usage(true))));
	}
}
