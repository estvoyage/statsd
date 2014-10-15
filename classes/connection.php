<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd
;

class connection implements statsd\connection
{
	private
		$host,
		$port,
		$socket
	;

	function __construct($host, $port, statsd\connection\socket $socket)
	{
		$this->host = $host;
		$this->port = $port;
		$this->socket = $socket;
	}

	function send($value, $timeout = null)
	{
		$this->socket->send($value, $this->host, $this->port, $timeout);

		return $this;
	}
}
