<?php

namespace estvoyage\statsd\probe;

use
	estvoyage\statsd\metric,
	estvoyage\statsd\world as statsd
;

final class counter
{
	private
		$client,
		$start
	;

	function __construct(statsd\client $client, $start = 0)
	{
		$this->client = $client;
		$this->start = $start;
	}

	function increment($bucket, $value = 1)
	{
		$this->client->metricsAre($this->buildMetric($bucket, $value));

		return $this;
	}

	function decrement($bucket, $value = 1)
	{
		$this->client->metricsAre($this->buildMetric($bucket, - $value));

		return $this;
	}

	private function buildMetric($bucket, $value)
	{
		return new metric\counting($bucket, $this->start + $value);
	}
}
