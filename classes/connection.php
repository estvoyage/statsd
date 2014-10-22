<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd
;

class connection implements statsd\connection
{
	private
		$buffer,
		$socket
	;

	function __construct(statsd\address $address)
	{
		$this->openSocket($address)->buffer = '';
	}

	function open(statsd\address $address, callable $callback)
	{
		$connection = clone $this;

		$callback($connection->openSocket($address));

		return $this;
	}

	function startPacket(callable $callback)
	{
		$connection = clone $this;
		$connection->buffer = '';

		$callback($connection);

		return $this;
	}

	function write($data, callable $callback)
	{
		$connection = clone $this;
		$connection->buffer .= $data;

		$callback($connection);

		return $this;
	}

	function endPacket(callable $callback)
	{
		$this->socket->write($this->buffer);

		$connection = clone $this;
		$connection->buffer = '';

		$callback($connection);

		return $this;
	}

	function close(callable $callback)
	{
		$connection = clone $this;

		$connection->socket
			->close(function($socket) use ($connection) {
					$connection->socket = $socket;
				}
			)
		;

		$callback($connection);

		return $this;
	}

	private function openSocket(statsd\address $address)
	{
		try
		{
			$address
				->openSocket(new socket, function($socket) {
						$this->socket = $socket;
					}
				)
			;
		}
		catch (\exception $exception)
		{
			throw new connection\exception('Unable to open connection');
		}

		return $this;
	}
}
