<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd
;

class packet implements statsd\packet
{
	private
		$metrics
	;

	function __construct()
	{
		$this->metrics = [];
	}

	function writeOn(statsd\connection $connection, callable $callback)
	{
		$callback = function($connection) use ($callback) {
			$connection->endPacket($callback);
		};

		$metric = end($this->metrics);

		while ($metric)
		{
			$callback = function($connection) use ($metric, $callback) {
				$connection->writeMetric($metric, $callback);
			};

			$metric = prev($this->metrics);
		}

		$callback = function($connection) use ($callback) {
			$connection->startPacket($callback);
		};

		$callback($connection);

		return $this;
	}

	function add(statsd\metric $metric, callable $callback)
	{
		$packet = clone $this;
		$packet->metrics[] = $metric;

		$callback($packet);

		return $this;
	}
}
