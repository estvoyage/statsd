<?php

namespace estvoyage\statsd\client;

use
	estvoyage\net,
	estvoyage\data,
	estvoyage\statsd,
	estvoyage\statsd\metric
;

final class etsy extends generic
{
	function __construct(metric\consumer $metricConsumer)
	{
		parent::__construct($metricConsumer, new metric\template\etsy);
	}
}
