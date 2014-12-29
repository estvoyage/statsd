<?php

namespace estvoyage\statsd\world\packet;

use
	estvoyage\statsd\world as statsd,
	estvoyage\statsd\metric
;

interface builder
{
	function useMetrics(metric $metric, metric... $metrics);
	function packetShouldBeSendOn(statsd\connection $connection);
}
