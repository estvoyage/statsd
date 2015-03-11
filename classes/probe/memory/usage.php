<?php

namespace estvoyage\statsd\probe\memory;

use
	estvoyage\statsd,
	estvoyage\statsd\metric
;

final class usage extends statsd\probe
{
	private
		$start
	;

	function __construct()
	{
		parent::__construct();

		$this->start = memory_get_usage(true);
	}

	function newBucket(metric\bucket $bucket)
	{
		return $this->newMetric(new metric\gauge($bucket, new metric\value(memory_get_usage(true) - $this->start)));
	}
}
