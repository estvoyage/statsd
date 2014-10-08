<?php

namespace seshat\statsd\connection;

use
	seshat\statsd\world\packet,
	seshat\statsd\world\connection
;

class socket implements connection\socket
{
	function sendPacketTo(packet $packet, $host, $port, $timeout = null)
	{
		$packet->writeOnSocket($this, $host, $port, $timeout);

		return $this;
	}

	function sendTo($data, $host, $port, $timeout = null)
	{
	}
}
