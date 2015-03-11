<?php

namespace estvoyage\statsd\probe\memory;

use
	estvoyage\statsd\probe,
	estvoyage\statsd\metric
;

final class usage extends probe\generic
{
	private
		$start
	;

	function __construct()
	{
		parent::__construct();

		$this->start = memory_get_usage(true);
	}

	function newStatsdBucket(metric\bucket $bucket)
	{
		return $this->newStatsdMetric(new metric\gauge($bucket, new metric\value(memory_get_usage(true) - $this->start)));
	}
}
