<?php

namespace estvoyage\statsd;

interface client
{
	function newStatsdMetric(metric $metric);
	function statsdMetricProviderIs(metric\provider $provider);
}
