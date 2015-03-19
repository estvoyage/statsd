<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\statsd
;

interface factory
{
	function statsdMetricConsumerIs(consumer $consumer);
	function newStatsdMetric(statsd\metric $metric);
	function statsdMetricProviderIs(provider $provider);
}
