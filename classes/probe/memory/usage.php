<?php

namespace estvoyage\statsd\probe\memory;

use
	estvoyage\statsd,
	estvoyage\statsd\metric
;

final class usage implements metric\provider
{
	private
		$packet,
		$start
	;

	function __construct()
	{
		$this->packet = new metric\packet;
		$this->start = memory_get_usage(true);
	}

	function newBucket(metric\bucket $bucket)
	{
		$this->packet
			->newMetric(
				new metric\gauge($bucket, new metric\value(memory_get_usage(true) - $this->start))
			)
		;

		return $this;
	}

	function statsdClientIs(statsd\client $client)
	{
		$client->statsdMetricProviderIs($this);

		return $this;
	}

	function statsdMetricFactoryIs(metric\factory $factory)
	{
		$factory->newStatsdMetric($this->packet);

		return $this;
	}
}
