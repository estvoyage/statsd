<?php

namespace estvoyage\statsd;

use
	estvoyage\net\mtu,
	estvoyage\net\socket,
	estvoyage\net\socket\data,
	estvoyage\net\socket\client\writeBuffer
;

final class packet implements connection\writer
{
	private
		$metrics
	;

	function __construct(metric ...$metrics)
	{
		$this->metrics = $metrics;
	}

	function socketHasMtu(socket\client\socket $socket, mtu $mtu)
	{
		if ($this->metrics)
		{
			$metrics = '';

			$buffer = $socket->buildWriteBuffer();

			foreach ($this->metrics as $metric)
			{
				$metric .= "\n";

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
