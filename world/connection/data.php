<?php

namespace estvoyage\statsd\world\connection;

use
	estvoyage\statsd\world as statsd
;

interface data
{
	function writeOn(statsd\connection $connection, callable $callback);
}
