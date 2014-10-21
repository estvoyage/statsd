<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\value,
	estvoyage\statsd\world as statsd
;

class host implements statsd\host
{
	private
		$host
	;

	function __construct($host)
	{
		$this->host = $host;
	}

	function openSocket(statsd\socket $socket, statsd\port $port, callable $callback)
	{
		$port->openSocket($socket, $this->host, $callback);

		return $this;
	}
}
