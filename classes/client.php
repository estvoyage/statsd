<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd,
	estvoyage\statsd\metric
;

final class client implements statsd\client
{
	private
		$connection,
		$builder
	;

	function __construct(statsd\connection $connection, statsd\packet\builder $builder = null)
	{
		$this->connection = $connection;
		$this->builder = $builder ?: new packet\builder;
	}

	function noMoreMetric()
	{
		$this->builder->packetShouldBeSendOn($this->connection);

		return $this;
	}

	function metricsAre(metric $metric, metric... $metrics)
	{
		array_unshift($metrics, $metric);

		$this->builder->useMetrics(... $metrics);

		return $this;
	}
}
