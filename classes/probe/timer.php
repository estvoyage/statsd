<?php

namespace estvoyage\statsd\probe;

use
	estvoyage\statsd,
	estvoyage\statsd\metric
;

final class timer extends statsd\probe
{
	private
		$start
	;

	function __construct()
	{
		parent::__construct();

		$this->start = self::now();
	}

	function newBucket(metric\bucket $bucket)
	{
		return $this->newMetric(new metric\timing($bucket, new metric\value(self::now() - $this->start)));
	}

	private static function now()
	{
		return microtime(true) * 10000;
	}
}
