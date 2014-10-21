<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd
;

class address implements statsd\address
{
	private
		$host,
		$port
	;

	function __construct(statsd\host $host = null, statsd\port $port = null)
	{
		$this->host = $host ?: new host('127.0.0.1');
		$this->port = $port ?: new port\statsd;
	}

	function openSocket(statsd\socket $socket, callable $callback)
	{
		$this->host->openSocket($socket, $this->port, $callback);

		return $this;
	}
}
