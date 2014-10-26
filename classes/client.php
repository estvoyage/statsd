<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd,
	estvoyage\statsd\bucket,
	estvoyage\statsd\value,
	estvoyage\statsd\packet
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
		$packet->writeOn($this->connection, function() {});

		return $this;
	}

	function sendTiming($bucket, $timing, $sampling = null)
	{
		$this->connection->writePacket(new packet\timing($bucket, $timing, $sampling), function() {});

		return $this;
	}
}
