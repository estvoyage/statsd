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

	function __construct(statsd\host $host, statsd\port $port)
	{
		$this->host = $host;
		$this->port = $port;
	}

	function openSocket(statsd\socket $socket, callable $callback)
	{
		$this->host->openSocket($socket, $this->port, $callback);

		return $this;
	}
}
