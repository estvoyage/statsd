<?php

namespace estvoyage\statsd\metric\factory;

use
	estvoyage\data,
	estvoyage\statsd\metric
;

class etsy implements metric\factory
{
	private
		$provider
	;

	function newStatsdMetric(metric $metric)
	{
	}

	function statsdMetricProviderIs(metric\provider $provider)
	{
		$this->provider = $provider;

		return $this;
	}

	function statsdMetricConsumerIs(metric\consumer $consumer)
	{
	}
}
