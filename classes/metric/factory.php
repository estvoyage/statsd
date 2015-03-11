<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\statsd
;

interface factory
{
	function newStatsdMetric(statsd\metric $metric);
	function statsdMetricProviderIs(provider $provider);
}
