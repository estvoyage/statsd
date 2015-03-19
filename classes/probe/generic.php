<?php

namespace estvoyage\statsd\probe;

use
	estvoyage\statsd,
	estvoyage\statsd\metric
;

abstract class generic implements statsd\probe
{
	private
		$packet
	;

	function __construct()
	{
		$this->packet = new metric\packet;
	}

	function statsdClientIs(statsd\client $client)
	{
		$client->newStatsdMetric($this->packet);

		return $this;
	}

	protected function newStatsdMetric(metric $metric)
	{
		$this->packet->newStatsdMetric($metric);

		return $this;
	}
}
