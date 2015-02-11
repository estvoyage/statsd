<?php

namespace estvoyage\statsd\probe\memory;

use
	estvoyage\statsd\metric
;

final class peak implements metric\builder
{
	private
		$collector
	;

	function __construct(metric\value\collector $valueCollector)
	{
		$this->valueCollector = $valueCollector;
	}

	function bucketIs(metric\bucket $bucket)
	{
		$this->valueCollector->valueGoesInto(metric\value::gauge(memory_get_peak_usage(true)), $bucket);

		return $this;
	}
}
