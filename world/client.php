<?php

namespace estvoyage\statsd\world;

use
	estvoyage\statsd\world as statsd,
	estvoyage\statsd\metric
;

interface client
{
	function valueGoesInto(metric\value $value, metric\bucket $bucket);
	function noMoreValue();
}
