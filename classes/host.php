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
		if (! preg_match('/^[0-9a-z][0-9a-z-]{0,62}(?:\.[0-9a-z-]{1,63}){0,3}$/i', $host))
		{
			throw new host\exception('\'' . $host . '\' is not a valid host');
		}

		$this->host = $host;
	}

	function openSocket(statsd\socket $socket, statsd\port $port, callable $callback)
	{
		$port->openSocket($socket, $this->host, $callback);

		return $this;
	}
}
