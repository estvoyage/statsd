<?php

namespace estvoyage\statsd\metric\factory;

use
	estvoyage\data,
	estvoyage\statsd\metric
;

final class etsy implements metric\factory
{
	private
		$metricTemplate
	;

	function __construct(data\consumer $dataConsumer)
	{
		$this->metricTemplate = new metric\template\etsy($dataConsumer);
	}

	function newStatsdMetric(metric $metric)
	{
		$metric->statsdMetricTemplateIs($this->metricTemplate);

		return $this;
	}

	function statsdMetricProviderIs(metric\provider $provider)
	{
		$provider->statsdMetricFactoryIs($this);

		return $this;
	}
}
