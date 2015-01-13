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

	function mark($bucket)
	{
		$this->client->metricsAre(new metric\gauge($bucket, memory_get_usage(true) - $this->start));

		return $this;
	}
}
