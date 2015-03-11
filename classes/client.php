<?php

namespace estvoyage\statsd;

use
	estvoyage\data
;

interface client extends data\provider
{
	function newStatsdMetric(metric $metric);
	function statsdMetricProviderIs(metric\provider $provider);
}
