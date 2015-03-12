<?php

namespace estvoyage\statsd;

use
	estvoyage\data
;

interface client
{
	function newStatsdMetric(metric $metric);
	function statsdMetricProviderIs(metric\provider $provider);
}
