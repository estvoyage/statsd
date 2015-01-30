<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd,
	estvoyage\statsd\metric
;

final class client implements statsd\client
{
	private
		$connection
	;

	function __construct(statsd\connection $connection)
	{
		$this->connection = $connection;

		$this->init();
	}

	function __destruct()
	{
		$this->noMoreMetric();
	}

	function noMoreMetric()
	{
		$this->connection->newPacket(new packet(... $this->metrics));

		return $this->init();
	}

	function newMetric(metric $metric)
	{
		$this->metrics[] = $metric;

		return $this;
	}

	function newTiming(metric\bucket $bucket, metric\value $value)
	{
		return $this->newMetric(new metric\timing($bucket, $value));
	}

	function newCounting(metric\bucket $bucket, metric\value $value = null)
	{
		return $this->newMetric(new metric\counting($bucket, $value));
	}

	function newMetrics(metric $metric1, metric $metric2, metric... $metrics)
	{
		array_unshift($metrics, $metric1, $metric2);

		$this->metrics = array_merge($this->metrics, $metrics);

		return $this;
	}

	private function init()
	{
		$this->metrics = [];

		return $this;
	}
}
