<?php

namespace seshat\statsd\connection;

use
	seshat\statsd\world\packet,
	seshat\statsd\world\connection
;

class socket implements connection\socket
{
	private
		$writer,
		$address,
		$resource
	;

	function __construct(connection\socket\writer $writer)
	{
		$this->writer = $writer;
	}

	function __destruct()
	{
		if (is_resource($this->resource))
		{
			fclose($this->resource);
		}
	}

	function sendPacketTo(packet $packet, $host, $port, $timeout = null)
	{
		$packet->writeOnSocket($this, $host, $port, $timeout);

		return $this;
	}

	function sendTo($data, $host, $port, $timeout = null)
	{
		$address = $host . ':' . $port;

		if ($this->address != $address)
		{
			$this->address = $address;
			$this->resource = fsockopen('udp://' . $host, $port, $errno, $error, $timeout) ?: null;

			if (! $this->resource)
			{
				throw new socket\exception('Unable to connect on host \'' . $host . '\' on port \'' . $port . '\': ' . $error);
			}
		}

		$this->writer->writeOnResource($data, $this->resource);

		return $this;
	}
}
