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
		$this->adds($metrics, function($packet) {
				$this->metrics = $packet->metrics;
			}
		);
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
		array_unshift($packet->metrics, $metric);

		$callback($packet);

		return $this;
	}

	function adds(array $metrics, callable $callback)
	{
		call_user_func(
			array_reduce(
				array_reverse($metrics),
				function($callback, $metric) { return function($packet) use ($metric, $callback) { $packet->add($metric, $callback); }; },
				$callback
			),
			$this
		);

		return $this;
	}
}
