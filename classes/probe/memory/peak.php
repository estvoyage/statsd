<?php

namespace estvoyage\statsd\probe\memory;

use
	estvoyage\statsd,
	estvoyage\statsd\metric
;

final class peak implements metric\provider
{
	private
		$packet
	;

	function __construct()
	{
		$this->packet = new metric\packet;
	}

	function newBucket(metric\bucket $bucket)
	{
		$this->packet
			->newMetric(
				new metric\gauge($bucket, new metric\value(memory_get_peak_usage(true)))
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
