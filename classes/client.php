<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd,
	estvoyage\statsd\metric,
	estvoyage\statsd\bucket,
	estvoyage\statsd\value
;

class client
{
	private
		$connection
	;

	function __construct(statsd\connection $connection)
	{
		$this->connection = $connection;
	}

	function send(statsd\packet $packet)
	{
		$this->connection->send($packet);

		return $this;
	}

	function sendMetric(metric $metric)
	{
		return $this->send(new packet($metric));
	}

	function gauge($bucket, $value)
	{
		return $this->sendMetric(new metric\gauge($bucket, $value));
	}

	function timing($bucket, $value)
	{
		return $this->sendMetric(new metric\timing($bucket, $value));
	}

	function counting($bucket, $value, $sampling = null)
	{
		return $this->sendMetric(new metric\counting($bucket, $value, $sampling));
	}

	function increment($bucket, $sampling = null, $value = null)
	{
		return $this->counting($bucket, $value ?: 1, $sampling);
	}

	function decrement($bucket, $sampling = null, $value = null)
	{
		return $this->counting($bucket, - ($value ?: 1), $sampling);
	}

	function set($bucket, $value)
	{
		return $this->sendMetric(new metric\set($bucket, $value));
	}
}
