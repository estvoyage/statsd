<?php

namespace estvoyage\statsd\probe;

use
	estvoyage\statsd\probe,
	estvoyage\statsd\metric
;

final class timer extends probe\generic
{
	private
		$start
	;

	function __construct()
	{
		parent::__construct();

		$this->start = self::now();
	}

	function newStatsdBucket(metric\bucket $bucket)
	{
		return $this->newStatsdMetric(new metric\timing($bucket, new metric\value(self::now() - $this->start)));
	}

	private static function now()
	{
		return (int) microtime(true) * 10000;
	}
}
