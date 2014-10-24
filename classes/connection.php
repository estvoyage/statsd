<?php

namespace estvoyage\statsd;

use
	estvoyage\statsd\connection,
	estvoyage\statsd\world as statsd
;

class connection implements statsd\connection
{
	private
		$mtu,
		$socket
	;

	function __construct(statsd\address $address, statsd\connection\mtu $mtu)
	{
		$this->openSocket($address)->mtu = $mtu;
	}

	function open(statsd\address $address, callable $callback)
	{
		$connection = clone $this;

		$callback($connection->openSocket($address));

		return $this;
	}

	function startPacket(callable $callback)
	{
		$this->mtu
			->reset(function($mtu) use ($callback) {
					$connection = clone $this;
					$connection->mtu = $mtu;

					$callback($connection);
				}
			)
		;

		return $this;
	}

	function write($data, callable $callback)
	{
		try
		{
			$this->mtu
				->add($data, function($mtu) use ($callback) {
						$connection = clone $this;
						$connection->mtu = $mtu;

						$callback($connection);
					}
				)
			;
		}
		catch (\exception $exception)
		{
			throw new connection\exception('MTU size exceeded');
		}

		return $this;
	}

	function endPacket(callable $callback)
	{
		$this->mtu
			->writeOn($this->socket, function($mtu) use ($callback) {
					$connection = clone $this;
					$connection->mtu = $mtu;

					$callback($connection);
				}
			)
		;

		return $this;
	}

	function close(callable $callback)
	{
		$this->socket
			->close(function($socket) use ($callback) {
					$connection = clone $this;
					$connection->socket = $socket;

					$callback($connection);
				}
			)
		;

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
