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

	function send(statsd\packet $packet)
	{
		$this->connection->send($packet);

		return $this;
	}
}
