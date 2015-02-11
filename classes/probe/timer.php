<?php

namespace estvoyage\statsd\probe;

use
	estvoyage\statsd\metric
;

final class timer implements metric\builder
{
	private
		$valueCollector,
		$start
	;

	function __construct(metric\value\collector $valueCollector)
	{
		$this->valueCollector = $valueCollector;
		$this->start = self::now();
	}

	function bucketIs(metric\bucket $bucket)
	{
		$this->valueCollector->valueGoesInto(metric\value::timing(self::now() - $this->start), $bucket);

		return $this;
	}

	private static function now()
	{
		return microtime(true) * 10000;
	}
}
