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

	function socketHasMtu(socket $socket, mtu $mtu)
	{
		$metrics = $this->metrics;

		if ($metrics)
		{
			$buffer = new packet\buffer($socket);

			while (strlen($metrics) > $mtu->asInteger)
			{
				if (! ($endOfPacket = strrpos($metrics, "\n", $mtu->asInteger)))
				{
					throw new mtu\overflow('Unable to split packet according to MTU');
				}

				$buffer->newData(new data(substr($metrics, 0, $endOfPacket)));

				$metrics = substr($metrics, $endOfPacket + 1);
			}

			$buffer->newData(new data($metrics));
		}

		return $this;
	}
}
