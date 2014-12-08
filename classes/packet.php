<?php

namespace estvoyage\statsd;

use
	estvoyage\value\world as value,
	estvoyage\net\socket
;

class packet
{
	use value\immutable;

	function __construct(metric ...$metrics)
	{
		$this->init(['data' => new socket\data((string) $metrics[0]) ]);

		$metrics = array_slice($metrics, 1);

		if ($metrics)
		{
			$this->addMetric(...$metrics);
		}
	}

	function __toString()
	{
		return (string) $this->data;
	}

	function add(metric ...$metrics)
	{
		$packet = clone $this;

		foreach ($metrics as $metric)
		{
			$packet->addMetric($metric);
		}

		return $packet;
	}

	private function addMetric(metric $metric)
	{
		$this->values['data'] = new socket\data($this. "\n" . $metric);

		return $this;
	}
}
