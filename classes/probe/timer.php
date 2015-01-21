<?php

namespace estvoyage\statsd\probe;

use
	estvoyage\statsd\metric,
	estvoyage\statsd\world as statsd
;

final class timer
{
	private
		$client,
		$start
	;

	function __construct(statsd\client $client)
	{
		$this->client = $client;
		$this->start = self::now();
	}

	function useBucket(metric\bucket $bucket)
	{
		$this->client->newMetric(new metric\timing($bucket, new metric\value(self::now() - $this->start)));

		return $this;
	}

	private static function now()
	{
		return microtime(true) * 10000;
	}
}
