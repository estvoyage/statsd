<?php

namespace estvoyage\statsd\metric\template;

use
	estvoyage\net,
	estvoyage\data,
	estvoyage\statsd\metric
;

class etsy implements metric\template
{
	private
		$metric
	;

	function statsdMetricConsumerIs(metric\consumer $metricConsumer)
	{
	}

	function mtuOfStatsdMetricConsumerIs(metric\consumer $metricConsumer, net\mtu $mtu)
	{
	}

	function newStatsdMetric(metric $metric)
	{
		$this->metric[] = $metric;

		return $this;
	}

	function statsdCountingContainsBucketAndValueAndSampling(metric\bucket $bucket, metric\value $value, metric\sampling $sampling = null)
	{
	}

	function statsdTimingContainsBucketAndValue(metric\bucket $bucket, metric\value $value)
	{
	}

	function statsdGaugeContainsBucketAndValue(metric\bucket $bucket, metric\value $value)
	{
	}

	function statsdGaugeUpdateContainsBucketAndValue(metric\bucket $bucket, metric\value $value)
	{
	}

	function statsdSetContainsBucketAndValue(metric\bucket $bucket, metric\value $value)
	{
	}
}
