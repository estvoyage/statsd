<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\net,
	estvoyage\data,
	estvoyage\statsd\metric
;

interface template
{
	function newStatsdMetric(metric $metric);
	function statsdCountingContainsBucketAndValueAndSampling(metric\bucket $bucket, metric\value $value, metric\sampling $sampling = null);
	function statsdTimingContainsBucketAndValue(metric\bucket $bucket, metric\value $value);
	function statsdGaugeContainsBucketAndValue(metric\bucket $bucket, metric\value $value);
	function statsdGaugeUpdateContainsBucketAndValue(metric\bucket $bucket, metric\value $value);
	function statsdSetContainsBucketAndValue(metric\bucket $bucket, metric\value $value);
	function statsdMetricConsumerIs(consumer $consumer);
	function mtuOfStatsdMetricConsumerIs(consumer $consumer, net\mtu $mtu);
}
