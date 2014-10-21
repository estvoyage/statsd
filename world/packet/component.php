<?php

namespace estvoyage\statsd\world\packet;

use
	estvoyage\statsd\world as statsd
;

interface component
{
	function writeOn(statsd\connection $connection, callable $callback);
}
