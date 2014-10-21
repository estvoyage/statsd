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
		if (! $port || $port < 0 || $port > 65535 || filter_var($port, FILTER_VALIDATE_INT) === false)
		{
			throw new port\exception('\'' . $port . '\' is not a valid port');
		}

		$this->port = $port;
	}

	function openSocket(statsd\socket $socket, $host, callable $callback)
	{
		$socket->open($host, $this->port, $callback);

		return $this;
	}
}
