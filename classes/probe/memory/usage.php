<?php

namespace estvoyage\statsd\probe\memory;

use
	estvoyage\statsd\metric
;

final class usage implements metric\builder
{
	private
		$valueCollector,
		$start
	;

	function __construct(metric\value\collector $valueCollector)
	{
		$this->valueCollector = $valueCollector;
		$this->start = memory_get_usage(true);
	}

	function bucketIs(metric\bucket $bucket)
	{
		$this->valueCollector
			->valueGoesInto(
				metric\value::gauge(memory_get_usage(true) - $this->start),
				$bucket
			)
		;

		return $this;
	}
}
