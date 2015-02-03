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

	function socketHasMtu(socket $socket, mtu $mtu)
	{
		if ($this->metrics)
		{
			$metrics = '';

			$buffer = new packet\buffer($socket);

			while ($this->metrics)
			{
				$metric = array_shift($this->metrics) . "\n";

				if (strlen($metric) > $mtu->asInteger && ! $metrics)
				{
					throw new mtu\overflow('Unable to split packet according to MTU');
				}

				if (strlen($metrics . $metric) > $mtu->asInteger)
				{
					$buffer->newData(new data($metrics));

					$metrics = '';
				}

				$metrics .= $metric;
			}

			if ($metrics)
			{
				$buffer->newData(new data($metrics));
			}
		}

		return $this;
	}
}
