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

	function writeOn(statsd\connection $connection)
	{
		return array_reduce($this->metrics, function($connection, $metric) { return $connection->writeData($metric); }, $connection->startPacket())->endPacket();
	}

	function add(statsd\metric $metric)
	{
		$packet = clone $this;

		return $packet->addMetric($metric);
	}

	function adds(array $metrics)
	{
		$packet = clone $this;

		foreach ($metrics as $metric)
		{
			$packet->addMetric($metric);
		}

		return $packet;
	}

	protected function addMetric(statsd\metric $metric)
	{
		$this->metrics[] = $metric;

		return $this;
	}
}
