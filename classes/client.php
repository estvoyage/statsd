<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd
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

	function send(statsd\bucket $bucket, statsd\value $value, statsd\value\sampling $sampling = null, statsd\connection\socket\timeout $timeout = null)
	{
		$value->send($bucket, $this->connection, $sampling, $timeout);

		return $this;
	}
}
