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

	function __construct()
	{
		$this->metricTemplate = new metric\template\etsy;
	}

	function statsdMetricConsumerIs(metric\consumer $metricConsumer)
	{
		$metricConsumer->statsdMetricTemplateIs($this->metricTemplate);

		return $this;
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
