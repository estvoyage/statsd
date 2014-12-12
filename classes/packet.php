<?php

namespace estvoyage\statsd;

use
	estvoyage\net\mtu,
	estvoyage\net\address,
	estvoyage\net\socket\data,
	estvoyage\net\world\socket,
	estvoyage\statsd\world as statsd
;

final class packet implements statsd\packet
{
	private
		$metrics
	;

	function __construct(metric ...$metrics)
	{
		$this->metrics = $metrics;
	}

	function add(metric $metric, metric ...$metrics)
	{
		array_unshift($metrics, $metric);

		return new self(... array_merge($this->metrics, $metrics));
	}

	function writeOn(socket $socket, address $address, mtu $mtu)
	{
		$data = join("\n", $this->metrics);

		while (strlen($data) > $mtu->asInteger)
		{
			$endOfPacket = strrpos($data, "\n", $mtu->asInteger);

			if (! $endOfPacket)
			{
				throw new mtu\overflow('Unable to split packet according to MTU');
			}

			$socket->write(new data(substr($data, 0, $endOfPacket)), $address);

			$data = substr($data, $endOfPacket + 1);
		}

		if ($data)
		{
			$socket->write(new data($data), $address);
		}

		return $this;
	}
}
