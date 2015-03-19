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
		$metricConsumer,
		$metricTemplate
	;

	function __construct(metric\consumer $metricConsumer, metric\template $metricTemplate)
	{
		$this->metricConsumer = $metricConsumer;
		$this->metricTemplate = $metricTemplate;
	}

	function newStatsdMetric(metric $metric)
	{
		$this->metricTemplate->newStatsdMetric($metric);
		$this->metricConsumer->statsdMetricTemplateIs($this->metricTemplate);

		return $this;
	}

	function statsdMetricProviderIs(metric\provider $provider)
	{
		$provider->statsdClientIs($this);

		return $this;
	}
}
