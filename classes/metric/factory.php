<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\data,
	estvoyage\statsd
;

interface factory extends data\provider
{
	function newStatsdMetric(statsd\metric $metric);
	function statsdMetricProviderIs(provider $provider);
}
