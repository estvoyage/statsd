<?php

namespace estvoyage\statsd\world\value;

use
	estvoyage\statsd\world as statsd
;

interface sampling extends statsd\metric\component
{
	function writeOn(statsd\connection $connection, callable $callback);
}
