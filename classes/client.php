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
		return $this->send(new packet(new bucket($bucket), new value\timing($timing, new value\sampling($sampling))));
	}
}
