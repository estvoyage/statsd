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

	function __construct(metric\consumer $metricConsumer, metric\template $metricTemplate, metric\bucket $parentBucket = null)
	{
		$this->metricConsumer = $metricConsumer;
		$this->metricTemplate = $metricTemplate;
		$this->parentBucket = $parentBucket;
	}

	function newStatsdMetric(metric $metric)
	{
		$this->metricConsumer
			->statsdMetricTemplateIs($this->metricTemplate
				->newStatsdMetric(! $this->parentBucket ? $metric : $metric->parentBucketIs($this->parentBucket))
			)
		;

		return $this;
	}

	function statsdMetricProviderIs(metric\provider $provider)
	{
		$provider->statsdClientIs($this);

		return $this;
	}
}
