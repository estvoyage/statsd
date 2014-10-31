<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd
;

class socket implements statsd\socket
{
	private
		$resource
	;

	function open($host, $port)
	{
		$socket = clone $this;

		$socket->resource = fsockopen('udp://' . $host, $port, $errno, $error) ?: null;

		if (! $socket->resource)
		{
			throw new socket\exception('Unable to connect on host \'' . $host . '\' on port \'' . $port . '\': ' . $error);
		}

		return $socket;
	}

	function write($data)
	{
		if (! $this->resource)
		{
			throw new socket\exception('Socket is not open');
		}

		while ($data)
		{
			$bytesWritten = fwrite($this->resource, $data, strlen($data));

			if ($bytesWritten === false)
			{
				throw new socket\exception('Unable to write \'' . $data . '\'');
			}

			$data = substr($data, $bytesWritten);
		}

		return $this;
	}

	function close()
	{
		$socket = $this;

		if ($this->resource)
		{
			$socket = clone $this;

			if (! fclose($socket->resource))
			{
				throw new socket\exception('Unable to close');
			}

			$socket->resource = null;
		}

		return $socket;
	}
}
