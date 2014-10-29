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

		$callback = array_reduce($this->metrics, function($callback, $metric) { return function($connection) use ($metric, $callback) { $connection->writeData($metric, $callback); }; }, $callback);

		$callback = function($connection) use ($callback) {
			$connection->startPacket($callback);
		};

		$callback($connection);

		return $this;
	}

	function add(statsd\metric $metric, callable $callback)
	{
		$packet = clone $this;

		array_unshift($packet->metrics, $metric);

		$callback($packet);

		return $this;
	}
}
