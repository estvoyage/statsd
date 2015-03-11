<?php

namespace estvoyage\statsd\metric\etsy;

use
	estvoyage\data,
	estvoyage\statsd
;

class factory implements statsd\metric\factory
{
	private
		$dataConsumer
	;

	function __construct(data\consumer $dataConsumer)
	{
		$this->dataConsumer = $dataConsumer;
	}

	function newStatsdMetric(statsd\metric $metric)
	{
	}
}
