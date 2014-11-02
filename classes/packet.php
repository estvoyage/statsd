<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd,
	estvoyage\statsd\metric
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

	function addTiming($bucket, $value)
	{
		return $this->add(new metric\timing($bucket, $value));
	}

	function addGauge($bucket, $value)
	{
		return $this->add(new metric\gauge($bucket, $value));
	}

	function addCounting($bucket, $value)
	{
		return $this->add(new metric\counting($bucket, $value));
	}

	function addSet($bucket, $value)
	{
		return $this->add(new metric\set($bucket, $value));
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
