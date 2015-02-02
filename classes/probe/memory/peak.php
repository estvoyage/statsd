<?php

namespace estvoyage\statsd\probe\memory;

use
	estvoyage\statsd\metric,
	estvoyage\statsd\world as statsd
;

final class peak
{
	private
		$client
	;

	function __construct(statsd\client $client)
	{
		$this->client = $client;
	}

	function bucketIs(metric\bucket $bucket)
	{
		$this->client->valueGoesInto(metric\value::gauge(memory_get_peak_usage(true)), $bucket);

		return $this;
	}
}
