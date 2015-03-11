<?php

namespace estvoyage\statsd\client;

use
	estvoyage\net,
	estvoyage\data,
	estvoyage\statsd,
	estvoyage\statsd\metric
;

abstract class generic implements statsd\client
{
	private
		$metricFactory
	;

	function __construct(metric\factory $metricFactory)
	{
		$this->metricFactory = $metricFactory;
	}

	function newStatsdMetric(metric $metric)
	{
		return $this->statsdMetricProviderIs($metric);
	}

	function statsdMetricProviderIs(metric\provider $provider)
	{
		$provider->statsdMetricFactoryIs($this->metricFactory);

		return $this;
	}
}
