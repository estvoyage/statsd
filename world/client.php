<?php

namespace estvoyage\statsd\world;

use
	estvoyage\statsd\world as statsd,
	estvoyage\statsd\metric,
	estvoyage\statsd\bucket,
	estvoyage\statsd\value
;

interface client
{
	function noMoreMetric();
	function newMetric(metric $metric);
	function newMetrics(metric $metric1, metric $metric2, metric... $metrics);
}
