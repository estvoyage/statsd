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
		$this->metrics = join("\n", $metrics);
	}

	function shouldBeSendOn(socket $socket, mtu $mtu)
	{
		$metrics = $this->metrics;

		while (strlen($metrics) > $mtu->asInteger)
		{
			if (! ($endOfPacket = strrpos($metrics, "\n", $mtu->asInteger)))
			{
				throw new mtu\overflow('Unable to split packet according to MTU');
			}

			$socket->mustSend(new data(substr($metrics, 0, $endOfPacket)));

			$metrics = substr($metrics, $endOfPacket + 1);
		}

		$socket->mustSend(new data($metrics));

		return $this;
	}
}
