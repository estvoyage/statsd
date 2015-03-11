<?php

namespace estvoyage\statsd\probe;

use
	estvoyage\statsd,
	estvoyage\statsd\metric
;

final class timer implements metric\provider
{
	private
		$packet,
		$start
	;

	function __construct()
	{
		$this->packet = new metric\packet;
		$this->start = self::now();
	}

	function newBucket(metric\bucket $bucket)
	{
		$this->packet->newMetric(new metric\timing($bucket, new metric\value(self::now() - $this->start)));

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

	private static function now()
	{
		return microtime(true) * 10000;
	}
}
