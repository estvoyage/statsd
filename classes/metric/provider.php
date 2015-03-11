<?php

namespace estvoyage\statsd\metric;

use
	estvoyage\statsd
;

interface provider
{
	function statsdClientIs(statsd\client $client);
	function statsdMetricFactoryIs(factory $factory);
}