<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd,
	estvoyage\statsd\metric
;

abstract class probe implements metric\provider
{
	private
		$packet
	;

	function __construct()
	{
		$this->packet = new metric\packet;
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

	abstract function newStatsdBucket(metric\bucket $bucket);

	protected function newStatsdMetric(metric $metric)
	{
		$this->packet->newStatsdMetric($metric);

		return $this;
	}
}
