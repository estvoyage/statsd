<?php

namespace estvoyage\statsd\packet;

use
	estvoyage\statsd\metric,
	estvoyage\statsd\packet,
	estvoyage\statsd\world as statsd
;

class builder implements statsd\packet\builder
{
	private
		$metrics
	;

	function __construct(metric... $metrics)
	{
		$this->metrics = $metrics;
	}

	function useMetrics(metric $metric, metric... $metrics)
	{
		array_unshift($metrics, $metric);

		$this->metrics = array_merge($this->metrics, array_diff($metrics, $this->metrics));

		return $this;
	}

	function packetShouldBeSendOn(statsd\connection $connection)
	{
		$connection->packetShouldBeSend(new packet(... $this->metrics));

		$this->metrics = [];

		return $this;
	}
}
