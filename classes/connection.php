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
		$socket,
		$packets
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
			->resetIfTrue(! $this->packets, function($mtu) use ($callback) {
					$connection = clone $this;
					$connection->mtu = $mtu;
					$connection->packets++;

					$callback($connection);
				}
			)
		;

		return $this;
	}

	function startMetric(callable $callback)
	{
		$this->mtu
			->addIfNotEmpty("\n", function($mtu) use ($callback) {
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

	function endMetric(callable $callback)
	{
		$callback($this);

		return $this;
	}

	function endPacket(callable $callback)
	{
		try
		{
			$this->mtu
				->writeIfTrueOn($this->packets == 1, $this->socket, function($mtu) use ($callback) {
						$connection = clone $this;
						$connection->mtu = $mtu;
						$connection->packets--;

						$callback($connection);
					}
				)
			;
		}
		catch (\exception $exception)
		{
			throw new connection\exception('Unable to end packet');
		}

		return $this;
	}

	function writePacket(statsd\packet $packet, callable $callback)
	{
		$packet->writeOn($this, $callback);

		return $this;
	}

	function writeMetric(statsd\metric $metric, callable $callback)
	{
		$metric->writeOn($this, $callback);

		return $this;
	}

	function writeMetricComponent(statsd\metric\component $component, callable $callback)
	{
		$component->writeOn($this, $callback);

		return $this;
	}

	function close(callable $callback)
	{
		try
		{
			$this->socket
				->close(function($socket) use ($callback) {
						$connection = clone $this;
						$connection->socket = $socket;

						$callback($connection);
					}
				)
			;
		}
		catch (\exception $exception)
		{
			throw new connection\exception('Unable to close connection');
		}

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
