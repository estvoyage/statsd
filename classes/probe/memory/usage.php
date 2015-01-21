<?php

namespace estvoyage\statsd\probe\memory;

use
	estvoyage\statsd\metric,
	estvoyage\statsd\world as statsd
;

final class usage
{
	private
		$client,
		$start
	;

	function __construct(statsd\client $client)
	{
		$this->client = $client;
		$this->start = memory_get_usage(true);
	}

	function useBucket(metric\bucket $bucket)
	{
		$this->client->newMetric(new metric\gauge($bucket, new metric\value(memory_get_usage(true) - $this->start)));

		return $this;
	}
}
