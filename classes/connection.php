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

	function open(statsd\address $address)
	{
		$connection = clone $this;

		return $connection->openSocket($address);
	}

	function startPacket()
	{
		$connection = clone $this;
		$connection->mtu = $connection->mtu->resetIfTrue(! $this->packets);
		$connection->packets++;

		return $connection;
	}

	function startMetric()
	{
		return $this;
	}

	function write($data)
	{
		$connection = clone $this;

		try
		{
			$connection->mtu = $connection->mtu->add($data);
		}
		catch (\exception $exception)
		{
			throw new connection\exception($exception->getMessage());
		}

		return $connection;
	}

	function endMetric()
	{
		$connection = clone $this;
		$connection->mtu = $connection->mtu->addIfNotEmpty("\n");

		return $connection;
	}

	function endPacket()
	{
		$connection = clone $this;
		$connection->packets--;

		try
		{
			$connection->mtu = $connection->mtu->writeIfTrueOn($this->packets == 1, $this->socket);
		}
		catch (\exception $exception)
		{
			throw new connection\exception($exception->getMessage());
		}

		return $connection;
	}

	function close()
	{
		$connection = clone $this;

		try
		{
			$connection->socket = $connection->socket->close();
		}
		catch (\exception $exception)
		{
			throw new connection\exception($exception->getMessage());
		}

		return $connection;
	}

	function writeData(statsd\connection\data $data)
	{
		return $data->writeOn($this);
	}

	private function openSocket(statsd\address $address)
	{
		try
		{
			$this->socket = $address->openSocket($this->socket ?: new socket);
		}
		catch (\exception $exception)
		{
			throw new connection\exception($exception->getMessage());
		}

		return $this;
	}
}
