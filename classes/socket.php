<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd
;

class socket implements statsd\socket
{
	private
		$resource,
		$host,
		$port
	;

	function __construct($host = '127.0.0.1', $port = 8125)
	{
		$this->createSocket();

		$this->host = $host;
		$this->port = $port;
	}

	function __destruct()
	{
		socket_close($this->resource);
	}

	function __clone()
	{
		$this->createSocket();
	}

	function open($host, $port)
	{
		$socket = $this;

		if ($host != $this->host || $port != $this->port)
		{
			$socket = clone $this;
			$socket->host = $host;
			$socket->port = $port;
		}

		return $socket;
	}

	function write($data)
	{
		while ($data)
		{
			$bytesWritten = socket_sendto($this->resource, $data, strlen($data), 0, $this->host, $this->port);

			if ($bytesWritten === false)
			{
				throw new socket\exception(socket_strerror(socket_last_error($this->resource)));
			}

			$data = substr($data, $bytesWritten);
		}

		return $this;
	}

	function close()
	{
		return $this;
	}

	private function createSocket()
	{
		$resource = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

		if (! $resource)
		{
			throw new socket\exception(socket_strerror(socket_last_error()));
		}

		$this->resource = $resource;

		return $this;
	}
}
