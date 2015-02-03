<?php

namespace estvoyage\statsd\probe\memory;

use
	estvoyage\statsd\metric,
	estvoyage\statsd\world as statsd
;

final class usage implements statsd\probe
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

	function bucketIs(metric\bucket $bucket)
	{
		$this->client
			->valueGoesInto(
				metric\value::gauge(memory_get_usage(true) - $this->start),
				$bucket
			)
		;

		return $this;
	}
}
