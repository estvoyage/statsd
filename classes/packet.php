<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd
;

class packet implements statsd\packet
{
	private
		$metrics = []
	;

	function __construct(array $metrics = [])
	{
		foreach ($metrics as $metric)
		{
			$this->addMetric($metric);
		}
	}

	function writeOn(statsd\connection $connection, callable $callback)
	{
		$connection->startPacket(
			array_reduce(
				$this->metrics,
				function($callback, $metric) { return function($connection) use ($metric, $callback) { $connection->writeData($metric, $callback); }; },
				function($connection) use ($callback) { $connection->endPacket($callback); }
			)
		);

		return $this;
	}

	function add(statsd\metric $metric, callable $callback)
	{
		$packet = clone $this;

		$callback($packet->addMetric($metric));

		return $this;
	}

	function adds(array $metrics, callable $callback)
	{
		$packet = clone $this;

		foreach ($metrics as $metric)
		{
			$packet->addMetric($metric);
		}

		$callback($packet);

		return $this;
	}

	protected function addMetric(statsd\metric $metric)
	{
		array_unshift($this->metrics, $metric);

		return $this;
	}
}
