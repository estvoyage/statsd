<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\world as statsd
;

class connection implements statsd\connection
{
	private
		$buffer,
		$socket,
		$address
	;

	function __construct(statsd\address $address)
	{
		$this->buffer = '';
		$this->socket = new socket;
		$this->address = $address;
	}

	function open(callable $callback)
	{
		$connection = clone $this;

		$connection->address
			->openSocket($connection->socket, function($socket) use ($connection) {
					$connection->socket = $socket;
				}
			)
		;

		$callback($connection);

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
		$this
			->open(function($connection) use ($callback) {
					$connection
						->socket
							->write($connection->buffer)
					;

					$connection = clone $connection;
					$connection->buffer = '';

					$callback($connection);
				}
			)
		;

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
}
