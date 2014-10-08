<?php

namespace seshat\statsd;

use
	seshat\statsd\world as statsd
;

class packet implements statsd\packet
{
	private
		$value
	;

	function __construct($bucket, $value, $type, $sampleRate = 1)
	{
		$this->value = $bucket . ':' . $value . '|' . $type;

		if ($sampleRate != 1)
		{
			$this->value .= '|@' . $sampleRate;
		}
	}

	function writeOnSocket(statsd\connection\socket $socket, $host, $port, $timeout = null)
	{
		$socket->sendTo($this->value, $host, $port, $timeout);

		return $this;
	}
}
