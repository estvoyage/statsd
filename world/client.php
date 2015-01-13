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
	function metricsAre(metric $metric, metric... $metrics);
}
