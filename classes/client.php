<?php

namespace seshat\statsd;

use
	seshat\statsd\world as statsd
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

	function sendValue(statsd\value $value, statsd\bucket $bucket, $timeout = null)
	{
		$value->send($bucket, $this->connection, $timeout);

		return $this;
	}
}
