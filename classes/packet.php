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
		$data = [];

		$metrics = join("\n", $this->metrics);

		while (strlen($metrics) > $mtu->asInteger)
		{
			if (! ($endOfPacket = strrpos($metrics, "\n", $mtu->asInteger)))
			{
				throw new mtu\overflow('Unable to split packet according to MTU');
			}

			$data[] = new data(substr($metrics, 0, $endOfPacket));

			$metrics = substr($metrics, $endOfPacket + 1);
		}

		if ($metrics)
		{
			$data[] = new data($metrics);
		}

		foreach ($data as $dataNotWrited)
		{
			while ($dataNotWrited != new data)
			{
				$dataNotWrited = $socket->write($dataNotWrited, $address);
			}
		}

		return $this;
	}
}
