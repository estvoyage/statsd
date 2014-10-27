<?php

namespace estvoyage\statsd\world\metric;

use
	estvoyage\statsd\world as statsd
;

interface component
{
	function writeOn(statsd\connection $connection, callable $callback);
}
