<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd
;

class port implements statsd\port
{
	private
		$port
	;

	function __construct($port)
	{
		$this->port = $port;
	}

	function openSocket(statsd\socket $socket, $host, callable $callback)
	{
		$socket->open($host, $this->port, $callback);

		return $this;
	}
}
