<?php

namespace estvoyage\statsd\client;

use
	estvoyage\net,
	estvoyage\data,
	estvoyage\statsd,
	estvoyage\statsd\metric
;

final class etsy implements statsd\client
{
	private
		$metricFactory
	;

	function __construct(data\consumer $dataConsumer, net\mtu $mtu)
	{
		$this->metricFactory = new metric\factory\etsy(new metric\consumer($dataConsumer, $mtu));
	}

	function statsdMetricProviderIs(metric\provider $provider)
	{
		$provider->statsdMetricFactoryIs($this->metricFactory);

		return $this;
	}
}
