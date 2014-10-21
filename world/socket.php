<?php

namespace estvoyage\statsd\world;

use
	estvoyage\statsd\world as statsd
;

interface socket
{
	function open($host, $port, callable $callback);
	function write($data);
	function close(callable $callback);
}
