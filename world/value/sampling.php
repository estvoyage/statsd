<?php

namespace estvoyage\statsd\world\value;

use
	estvoyage\statsd\world as statsd
;

interface sampling
{
	function applyTo(statsd\value $value, callable $callback);
}
