<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd
;

class client implements statsd\client
{
	private
		$connection
	;

	function __construct(statsd\connection $connection)
	{
		$this->connection = $connection;
	}

	function send(statsd\connection\data $data)
	{
		$data->writeOn($this->connection);

		return $this;
	}
}
