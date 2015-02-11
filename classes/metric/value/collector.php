<?php

namespace estvoyage\statsd\metric\value;

use
	estvoyage\statsd\metric
;

interface collector
{
	function valueGoesInto(metric\value $value, metric\bucket $bucket);
	function noMoreValue();
}
